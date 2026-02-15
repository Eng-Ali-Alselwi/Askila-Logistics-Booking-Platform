<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingConfirmedMail;
use App\Mail\BookingCodeMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return view('dashboard.bookings.index');
    }

    public function create()
    {
        $flights = Flight::active()->upcoming()->with('bookings')->get();
        return view('dashboard.bookings.create', compact('flights'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_id_number' => 'nullable|string|max:20',
            'passport_number' => 'nullable|string|max:50',
            'passport_issue_date' => 'nullable|date',
            'passport_expiry_date' => 'nullable|date|after:passport_issue_date',
            'nationality' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'current_residence_country' => 'nullable|string|max:100',
            'destination_country' => 'nullable|string|max:100',
            'phone_sudan' => 'nullable|string|max:20',
            'travel_date' => 'nullable|date',
            'ticket_type' => 'required|in:one_way,round_trip',
            'seat_class' => 'required|in:economy,business,first',
            'cabin_type' => 'nullable|string|max:100',
            'number_of_passengers' => 'required|integer',
            'passenger_details' => 'nullable|array',
            'special_requests' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:on_arrival,whatsapp,tap_payment',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:4096'
        ]);

        $flight = Flight::findOrFail($request->flight_id);

        // التحقق من توفر المقاعد
        if ($flight->available_seats < $request->number_of_passengers) {
            return back()->withErrors(['flight_id' => 'لا يمكن إتمام الحجز لأن عدد المقاعد المطلوبة أكبر من العدد المتوفر حالياً في هذه الرحلة.'])->withInput();
        }

        // رفع صورة الجواز إن وجدت
        // $imagePath = 'bookings/default-booking.png';
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = (string) Str::uuid() . ($extension ? ('.' . strtolower($extension)) : '');
            $stored = $request->file('image')->storeAs('bookings', $randomName, 'public');
            $imagePath = $stored ?: $imagePath;
        }

        // حساب المبالغ (مطابقة لواجهة المستخدم الأمامية)
        $basePrice = $flight->getPriceForClass($request->seat_class);
        $totalAmount = $basePrice * $request->number_of_passengers;

        // مضاعفة السعر إذا كانت التذكرة ذهاب وإياب
        if ($request->ticket_type === 'round_trip') {
            $totalAmount *= 2;
        }

        // $taxAmount = $totalAmount * 0.15; // 15% ضريبة
        // $serviceFee = 50; // رسوم خدمة ثابتة

        try {
            DB::beginTransaction();
            $booking = Booking::create([
                'flight_id' => $request->flight_id,
                'customer_id' => null, // يمكن ربطه بعميل لاحقاً
                'passenger_name' => $request->passenger_name,
                'passenger_email' => $request->passenger_email,
                'passenger_phone' => $request->passenger_phone,
                'passenger_id_number' => $request->passenger_id_number,
                'passport_number' => $request->passport_number,
                'passport_issue_date' => $request->passport_issue_date,
                'passport_expiry_date' => $request->passport_expiry_date,
                'nationality' => $request->nationality,
                'date_of_birth' => $request->date_of_birth,
                'current_residence_country' => $request->current_residence_country,
                'destination_country' => $request->destination_country,
                'phone_sudan' => $request->phone_sudan,
                'travel_date' => $request->travel_date,
                'ticket_type' => $request->ticket_type,
                'seat_class' => $request->seat_class,
                'cabin_type' => $request->cabin_type,
                'number_of_passengers' => $request->number_of_passengers,
                'passenger_details' => $request->passenger_details,
                'total_amount' => $totalAmount,
                // 'tax_amount' => $taxAmount,
                // 'service_fee' => $serviceFee,
                'currency' => 'SAR',
                'status' => 'pending',
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'special_requests' => $request->special_requests,
                'image' => $imagePath,
                'created_by' => auth()->id()
            ]);

            // تحديث المقاعد المتاحة (إنقاص بعد الحجز)
            $flight->updateAvailableSeats(-$request->number_of_passengers);
            DB::commit();

            // إرسال بريد برقم الحجز بعد إنشاء الحجز من الأدمن (اختياري)
            try {
                if (!empty($booking->passenger_email)) {
                    Mail::to($booking->passenger_email)->send(new BookingCodeMail($booking));
                    \Log::info('Booking confirmation email sent successfully', [
                        'booking_id' => $booking->id,
                        'email' => $booking->passenger_email,
                        'payment_method' => $booking->payment_method
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('Failed to send booking code email (admin create)', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('dashboard.bookings.show', $booking)
                ->with('success', 'تم إنشاء الحجز بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'فشل في إنشاء الحجز.']);
        }
    }

    public function show(Booking $booking)
    {
        $booking->load(['flight', 'customer', 'payments', 'creator']);
        
        return view('dashboard.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $booking->load(['flight', 'customer']);
        
        return view('dashboard.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_id_number' => 'nullable|string|max:20',
            'current_residence_country' => 'nullable|string|max:100',
            'destination_country' => 'nullable|string|max:100',
            'phone_sudan' => 'nullable|string|max:20',
            'ticket_type' => 'required|in:one_way,round_trip',
            'seat_class' => 'required|in:economy,business,first',
            'number_of_passengers' => 'required|integer|min:1',
            'passenger_details' => 'nullable|array',
            'special_requests' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:4096'
        ]);

        try {
            DB::beginTransaction();

            $wasConfirmed = $booking->isConfirmed();
            $oldPassengerCount = (int) $booking->number_of_passengers;
            $newPassengerCount = (int) $validated['number_of_passengers'];
            
            // قفل سجلات الرحلات لمنع التعديل المتزامن (Race Condition)
            $oldFlight = Flight::where('id', $booking->flight_id)->lockForUpdate()->firstOrFail();
            $newFlight = ($validated['flight_id'] == $oldFlight->id) 
                ? $oldFlight 
                : Flight::where('id', $validated['flight_id'])->lockForUpdate()->firstOrFail();

            // تحديث المقاعد إذا تغيرت الرحلة أو تغير العدد
            if ($oldFlight->id != $newFlight->id) {
                // التحقق من توفر المقاعد في الرحلة الجديدة
                if ($newFlight->available_seats < $newPassengerCount) {
                    throw new \Exception('لا يمكن تغيير الرحلة لأن عدد المقاعد المطلوبة أكبر من العدد المتوفر حالياً في هذه الرحلة.');
                }
                
                // عملية النقل: إعادة للقديمة وخصم من الجديدة
                $oldFlight->increment('available_seats', $oldPassengerCount);
                $newFlight->decrement('available_seats', $newPassengerCount);
            } else {
                // نفس الرحلة، تحقق فقط إذا تغير العدد
                if ($oldPassengerCount != $newPassengerCount) {
                    $seatDifference = $newPassengerCount - $oldPassengerCount;
                    if ($seatDifference > 0 && $oldFlight->available_seats < $seatDifference) {
                        throw new \Exception('لا توجد مقاعد متاحة كافية في هذه الرحلة.');
                    }
                    
                    if ($seatDifference > 0) {
                        $oldFlight->decrement('available_seats', $seatDifference);
                    } else {
                        $oldFlight->increment('available_seats', abs($seatDifference));
                    }
                }
            }

            // معالجة الصورة وحذف القديمة إن وجدت
            $imagePath = $booking->image;
            if ($request->hasFile('image')) {
                if ($booking->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($booking->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->image);
                }
                
                $extension = $request->file('image')->getClientOriginalExtension();
                $randomName = (string) Str::uuid() . ($extension ? ('.' . strtolower($extension)) : '');
                $imagePath = $request->file('image')->storeAs('bookings', $randomName, 'public');
            }

            // حساب السعر الجديد بناءً على منطق الأعمال الحالي
            $pricePerSeat = $newFlight->getPriceForClass($validated['seat_class']);
            $totalAmount = $pricePerSeat * $newPassengerCount;

            if ($validated['ticket_type'] === 'round_trip') {
                $totalAmount *= 2;
            }

            // تحديث سجل الحجز
            $booking->update([
                'flight_id' => $newFlight->id,
                'passenger_name' => $validated['passenger_name'],
                'passenger_email' => $validated['passenger_email'],
                'passenger_phone' => $validated['passenger_phone'],
                'passenger_id_number' => $validated['passenger_id_number'],
                'current_residence_country' => $validated['current_residence_country'],
                'destination_country' => $validated['destination_country'],
                'phone_sudan' => $validated['phone_sudan'],
                'ticket_type' => $validated['ticket_type'],
                'seat_class' => $validated['seat_class'],
                'number_of_passengers' => $newPassengerCount,
                'passenger_details' => $validated['passenger_details'] ?? [],
                'total_amount' => $totalAmount,
                'special_requests' => $validated['special_requests'],
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'image' => $imagePath
            ]);

            DB::commit();

            // العمليات اللاحقة للالتزام (خارج الـ transaction لتوفير الموارد)
            if (!$wasConfirmed && $validated['status'] === 'confirmed') {
                try {
                    if (!empty($booking->passenger_email)) {
                        Mail::to($booking->passenger_email)->send(new BookingConfirmedMail($booking));
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Failed to send booking confirmed email', ['id' => $booking->id]);
                }
            }

            return redirect()->route('dashboard.bookings.index')->with('success', 'تم تحديث الحجز بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['flight_id' => $e->getMessage()])->withInput();
        }
    }

    public function cancel(Booking $booking)
    {
        if ($booking->isCancelled()) {
            return back()->withErrors(['error' => 'هذا الحجز مُلغى بالفعل.']);
        }

        $cancelled = $booking->cancel('إلغاء من قبل الإدارة');

        if ($cancelled) {
            return back()->with('success', 'تم إلغاء الحجز بنجاح.');
        }

        return back()->withErrors(['error' => 'فشل في إلغاء الحجز.']);
    }

    public function confirm(Booking $booking)
    {
        if ($booking->isConfirmed()) {
            return back()->withErrors(['error' => 'هذا الحجز مؤكد بالفعل.']);
        }

        $booking->confirm();

        // إرسال بريد تأكيد الحجز للمستخدم
        try {
            if (!empty($booking->passenger_email)) {
                Mail::to($booking->passenger_email)->send(new BookingConfirmedMail($booking));
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to send booking confirmed email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'تم تأكيد الحجز بنجاح.');
    }

    public function refund(Request $request, Booking $booking)
    {
        $request->validate([
            'refund_amount' => 'required|numeric|min:0|max:' . $booking->total_amount,
            'refund_reason' => 'required|string|max:500'
        ]);

        if (!$booking->isPaid()) {
            return back()->withErrors(['error' => 'لا يمكن استرداد مبلغ لحجز غير مدفوع.']);
        }

        try {
            DB::beginTransaction();

            // إنشاء سجل استرداد
            $refundPayment = \App\Models\Payment::create([
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => -$request->refund_amount, // مبلغ سالب للاسترداد
                'currency' => $booking->currency,
                'payment_method' => 'refund',
                'status' => 'completed',
                'gateway_transaction_id' => 'REFUND_' . time(),
                'gateway_response' => ['reason' => $request->refund_reason],
                'processed_at' => now(),
                'processed_by' => auth()->id()
            ]);

            // تحديث حالة الحجز
            $booking->update([
                'payment_status' => 'refunded',
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'استرداد: ' . $request->refund_reason
            ]);

            // إعادة المقاعد المتاحة
            $booking->flight->updateAvailableSeats($booking->number_of_passengers);

            DB::commit();

            return back()->with('success', 'تم استرداد المبلغ بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'فشل في استرداد المبلغ.']);
        }
    }

    public function export(Request $request)
    {
        $query = Booking::with(['flight', 'customer']);

        // تطبيق نفس الفلاتر
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('booking_reference', 'like', '%' . $request->search . '%')
                  ->orWhere('passenger_name', 'like', '%' . $request->search . '%')
                  ->orWhere('passenger_email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        // تصدير إلى CSV
        $filename = 'bookings_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // رؤوس الأعمدة
            fputcsv($file, [
                'رقم الحجز',
                'اسم الراكب',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'رقم الرحلة',
                'الوجهة',
                'تاريخ السفر',
                'فئة المقعد',
                'عدد الركاب',
                'المبلغ الإجمالي',
                'حالة الحجز',
                'حالة الدفع',
                'تاريخ الحجز'
            ]);

            // البيانات
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_reference,
                    $booking->passenger_name,
                    $booking->passenger_email,
                    $booking->passenger_phone,
                    $booking->flight->flight_number,
                    $booking->flight->departure_city . ' - ' . $booking->flight->arrival_city,
                    $booking->flight->departure_time->format('Y-m-d H:i'),
                    $booking->seat_class,
                    $booking->number_of_passengers,
                    $booking->total_amount + $booking->tax_amount + $booking->service_fee,
                    $booking->status,
                    $booking->payment_status,
                    $booking->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Booking $booking)
    {
        // التحقق من إمكانية الحذف
        // if ($booking->status === 'confirmed' && $booking->flight->departure_time > now()) {
        //     return back()->withErrors(['error' => 'لا يمكن حذف حجز مؤكد لرحلة لم تغادر بعد.']);
        // }

        try {
            DB::beginTransaction();

            // إعادة المقاعد المتاحة
            $booking->flight->updateAvailableSeats($booking->number_of_passengers);

            // حذف الحجز
            $booking->delete();

            DB::commit();

            return redirect()->route('dashboard.bookings.index')
                ->with('success', 'تم حذف الحجز بنجاح.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'فشل في حذف الحجز.']);
        }
    }
}
