<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreRequest ;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCodeMail;

class BookingController extends Controller
{
    public function payment(Booking $booking)
    {
        if ($booking->isPaid()) {
            return redirect()->route('booking.confirmation', $booking)
                ->with('info', 'تم دفع هذا الحجز مسبقاً.');
        }

        return view('front.bookings.payment', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:credit_card,debit_card,mada,visa,mastercard,apple_pay,paypal,manual_whatsapp',
            'card_number' => 'required_if:payment_method,credit_card,debit_card,visa,mastercard|nullable|string',
            'expiry_date' => 'required_if:payment_method,credit_card,debit_card,visa,mastercard|nullable|string',
            'cvv' => 'required_if:payment_method,credit_card,debit_card,visa,mastercard|nullable|string',
            'cardholder_name' => 'required_if:payment_method,credit_card,debit_card,visa,mastercard|nullable|string',
            'branch_id' => 'required_if:payment_method,manual_whatsapp|exists:branches,id'
        ]);

        if ($booking->isPaid()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'تم دفع هذا الحجز مسبقاً'
                ], 400);
            }
            return redirect()->route('booking.confirmation', $booking)
                ->with('info', 'تم دفع هذا الحجز مسبقاً.');
        }

        // معالجة الدفع عبر الواتساب
        if ($request->payment_method === 'manual_whatsapp') {
            return $this->processWhatsAppPayment($request, $booking);
        }

        try {
            DB::beginTransaction();

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
                'currency' => $booking->currency,
                'payment_method' => $request->payment_method,
                'status' => 'processing',
                'gateway_transaction_id' => 'TXN_' . time() . '_' . rand(1000, 9999),
                'processed_by' => null
            ]);

            // محاكاة معالجة الدفع (في التطبيق الحقيقي، هنا ستكون استدعاءات API للبوابة)
            $paymentSuccess = $this->simulatePayment($request->payment_method);

            if ($paymentSuccess) {
                $payment->markAsCompleted($payment->gateway_transaction_id, [
                    'status' => 'success',
                    'transaction_id' => $payment->gateway_transaction_id,
                    'processed_at' => now()->toISOString()
                ]);

                $booking->markAsPaid($request->payment_method, $payment->payment_reference);
                $booking->confirm();

                DB::commit();

                return redirect()->route('booking.confirmation', $booking)
                    ->with('success', 'تم الدفع بنجاح! سيتم إرسال التذكرة إلى بريدك الإلكتروني.');
            } else {
                $payment->markAsFailed('فشل في معالجة الدفع', [
                    'status' => 'failed',
                    'error' => 'Payment processing failed'
                ]);

                DB::rollBack();

                return back()->withErrors(['error' => 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى.']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'حدث خطأ أثناء معالجة الدفع. يرجى المحاولة مرة أخرى.']);
        }
    }

    public function confirmation(Booking $booking)
    {
        $booking->load(['flight', 'customer', 'payments']);
        
        return view('front.bookings.confirmation', compact('booking'));
    }


    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        if (!$booking->canBeCancelled()) {
            return back()->withErrors(['error' => 'لا يمكن إلغاء هذا الحجز في الوقت الحالي.']);
        }

        $cancelled = $booking->cancel($request->cancellation_reason);

        if ($cancelled) {
            return redirect()->route('booking.track')
                ->with('success', 'تم إلغاء الحجز بنجاح.');
        }

        return back()->withErrors(['error' => 'فشل في إلغاء الحجز.']);
    }

    public function trackForm()
    {
        return view('front.bookings.track-form');
    }

    /**
     * معالجة تتبع الحجز مع رسالة النجاح
     */
    public function trackWithSuccess(Request $request)
    {
        $bookingReference = $request->get('booking_reference') ?? session('booking_reference');
        
        if (!$bookingReference) {
            return redirect()->route('booking.track');
        }

        $booking = Booking::where('booking_reference', $bookingReference)
            ->with(['flight', 'customer', 'payments'])
            ->first();

        if (!$booking) {
            return redirect()->route('booking.track')
                ->withErrors(['error' => 'لم يتم العثور على حجز بهذا الرقم المرجعي.']);
        }

        return view('front.bookings.track-form', compact('booking'));
    }

    private function simulatePayment($method)
    {
        // محاكاة نجاح الدفع بنسبة 90%
        return rand(1, 10) <= 9;
    }

    private function processWhatsAppPayment(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            // تحميل العلاقات المطلوبة
            $booking->load('flight');

            // التحقق من وجود الرحلة
            if (!$booking->flight) {
                return response()->json([
                    'success' => false,
                    'error' => 'بيانات الرحلة غير متوفرة'
                ], 400);
            }

            // الحصول على بيانات الفرع
            $branch = \App\Models\Branch::find($request->branch_id);
            if (!$branch) {
                return response()->json([
                    'success' => false,
                    'error' => 'الفرع المختار غير موجود'
                ], 400);
            }
            
            // تحديث الحجز
            $booking->update([
                'payment_status' => 'pending_manual',
                'payment_method' => 'manual_whatsapp',
                'branch_id' => $branch->id,
                'status' => 'pending'
            ]);

            // إنشاء سجل الدفع
            $payment = Payment::create([
                'payable_type' => Booking::class,
                'payable_id' => $booking->id,
                'amount' => $booking->total_amount + $booking->tax_amount + $booking->service_fee,
                'currency' => $booking->currency,
                'payment_method' => 'manual_whatsapp',
                'status' => 'pending',
                'gateway_transaction_id' => 'WHATSAPP_' . time() . '_' . rand(1000, 9999),
                'processed_by' => null
            ]);

            DB::commit();

            // إعداد رسالة الواتساب
            $whatsappPhone = $branch->whatsapp_phone ?? $branch->phone;
            
            if (!$whatsappPhone) {
                return response()->json([
                    'success' => false,
                    'error' => 'رقم الواتساب غير متوفر للفرع المختار'
                ], 400);
            }
            
            // تنظيف رقم الهاتف (إزالة المسافات والرموز)
            $whatsappPhone = preg_replace('/[^0-9+]/', '', $whatsappPhone);
            
            // إضافة + إذا لم تكن موجودة
            if (!str_starts_with($whatsappPhone, '+')) {
                $whatsappPhone = '+' . $whatsappPhone;
            }
            
            $customerName = $booking->passenger_name;
            $tripNumber = $booking->flight->flight_number;
            $bookingReference = $booking->booking_reference;
            $totalAmount = number_format($booking->total_amount + $booking->tax_amount + $booking->service_fee);
            $departureDate = $booking->flight->departure_time->format('Y-m-d');
            $departureTime = $booking->flight->departure_time->format('H:i');
            $route = $booking->flight->departure_city . ' - ' . $booking->flight->arrival_city;
            $airline = $booking->flight->airline;
            $passengersCount = $booking->number_of_passengers;
            $seatClass = ucfirst($booking->seat_class);
            
            $whatsappMessage = "السلام عليكم ورحمة الله وبركاته%0A%0Aأنا {$customerName}%0A%0A📋 *تفاصيل الحجز:*%0A🔖 رقم الحجز: {$bookingReference}%0A✈️ رقم الرحلة: {$tripNumber}%0A🏢 شركة الطيران: {$airline}%0A🗺️ المسار: {$route}%0A📅 تاريخ السفر: {$departureDate}%0A🕐 وقت الإقلاع: {$departureTime}%0A👥 عدد المسافرين: {$passengersCount}%0A💺 فئة المقاعد: {$seatClass}%0A💰 المبلغ الإجمالي: {$totalAmount} ريال%0A%0Aأريد تأكيد الحجز وإتمام عملية الدفع.%0A%0Aشكراً لكم";
            $whatsappUrl = "https://wa.me/{$whatsappPhone}?text={$whatsappMessage}";

            // إرجاع رابط الواتساب للاستخدام في JavaScript
            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
                'message' => 'تم إرسال طلبك للحجز بنجاح',
                'booking_id' => $booking->id,
                'branch_name' => $branch->name
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // تسجيل الخطأ للتصحيح
            \Illuminate\Support\Facades\Log::error('WhatsApp Payment Error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'branch_id' => $request->branch_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function whatsappRequest(Request $request, \App\Models\Booking $booking)
    {
        try {
            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
            ]);

            // جلب بيانات الفرع
            $branch = \App\Models\Branch::findOrFail($validated['branch_id']);

            // تحديث بيانات الحجز
            $booking->update([
                'payment_method' => 'manual_whatsapp',
                'branch_id' => $branch->id,
                'status' => 'pending_confirmation',
            ]);

            // رقم الواتساب للفرع
            $whatsappNumber = preg_replace('/\D/', '', $branch->phone); // إزالة الرموز
            if (empty($whatsappNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'رقم الهاتف غير متوفر لهذا الفرع.',
                ], 400);
            }

            // إنشاء الرسالة التلقائية
            $message = urlencode(
                "مرحبًا، أنا العميل {$booking->passenger_name}\n".
                "رقم الرحلة: {$booking->flight->flight_number}\n".
                "رقم الحجز: {$booking->booking_reference}\n".
                "أريد تأكيد الحجز والدفع."
            );

            // إنشاء رابط واتساب مباشر
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";

            return response()->json([
                'success' => true,
                'whatsapp_url' => $whatsappUrl,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى اختيار الفرع الصحيح.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ داخلي: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * اختيار طريقة الدفع وإنشاء الحجز
     */
    public function choosePayment(StoreRequest $request, Flight $flight)
    {
        // ✅ التحقق من صحة البيانات
        $validatedData = $request->validated();

        // ✅ التحقق من وجود صورة الجواز (إلزامي)
        if (!$request->hasFile('image')) {
            return back()->withErrors(['image' => 'يجب إرفاق صورة جواز السفر أو الإقامة.'])
                ->withInput();
        }

        // ✅ التحقق من توفر المقاعد
        if (!$flight->canBook($request->number_of_passengers)) {
            \Log::error('Cannot book: insufficient seats', [
                'available_seats' => $flight->available_seats,
                'requested_seats' => $request->number_of_passengers
            ]);

            return back()->withErrors(['error' => 'لا توجد مقاعد متاحة كافية للرحلة المحددة.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            \Log::info('Starting booking process', [
                'payment_method' => $request->payment_method,
                'flight_id' => $flight->id
            ]);

            // ✅ رفع صورة الجواز (إلزامي)
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = (string) Str::uuid() . ($extension ? ('.' . strtolower($extension)) : '');
            $imagePath = $request->file('image')->storeAs('bookings', $randomName, 'public');

            if (!$imagePath) {
                throw new \Exception('فشل رفع صورة الجواز. يرجى المحاولة مرة أخرى.');
            }

            // ✅ حساب الأسعار
            $pricePerSeat = $flight->getPriceForClass($request->seat_class);
            $totalAmount = $pricePerSeat * $request->number_of_passengers;
            // ✅ إنشاء أو العثور على العميل
            $customer = Customer::firstOrCreate(
                ['email' => $request->passenger_email],
                [
                    'name' => $request->passenger_name,
                    'phone' => $request->passenger_phone,
                    'is_active' => true
                ]
            );

            // ✅ تحديد حالة الحجز بناءً على طريقة الدفع
            $bookingStatus = 'pending';
            $paymentStatus = 'pending';
            
            // جميع طرق الدفع الحالية تُعامل بنفس الطريقة (حجز مباشر)
            // Tap Payment سيتم تفعيله لاحقاً
            
            // ✅ إنشاء الحجز
            $booking = Booking::create([
                'flight_id' => $flight->id,
                'customer_id' => $customer->id,
                'passenger_name' => $request->passenger_name,
                'passenger_email' => $request->passenger_email,
                'passenger_phone' => $request->passenger_phone,
                'passenger_id_number' => $request->passenger_id_number,
                'passport_number' => $request->passport_number,
                'phone_sudan' => $request->phone_sudan,
                'ticket_type' => $request->ticket_type,
                'seat_class' => $request->seat_class,
                'number_of_passengers' => $request->number_of_passengers,
                'passenger_details' => $request->passenger_details,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'service_fee' => $serviceFee,
                'currency' => 'SAR',
                'status' => $bookingStatus,
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'special_requests' => $request->special_requests,
                'image' => $imagePath,
                'created_by' => auth()->id() ?? null
            ]);

            // ✅ تحديث المقاعد المتاحة
            $flight->updateAvailableSeats(-$request->number_of_passengers);

            DB::commit();

            // ✅ إرسال بريد إلكتروني برقم الحجز لجميع طرق الدفع
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
                \Log::warning('Failed to send booking confirmation email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            \Log::info('Booking created successfully', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'status' => $booking->status,
                'payment_status' => $booking->payment_status,
                'payment_method' => $booking->payment_method
            ]);

            // ✅ رسالة النجاح بناءً على طريقة الدفع
            $successMessage = '';
            
            switch ($request->payment_method) {
                case 'on_arrival':
                    $successMessage = 'تم الحجز بنجاح! يمكنك الدفع عند الحضور إلى المكتب. رقم الحجز الخاص بك: ' . $booking->booking_reference;
                    break;
                    
                case 'whatsapp':
                    $successMessage = 'تم الحجز بنجاح! سيتم التواصل معك عبر الواتساب قريباً. رقم الحجز الخاص بك: ' . $booking->booking_reference;
                    break;
                    
                case 'tap_payment':
                    $successMessage = 'تم الحجز بنجاح! رقم الحجز الخاص بك: ' . $booking->booking_reference . ' (ملاحظة: Tap Payment سيتم تفعيله قريباً)';
                    break;
                    
                default:
                    $successMessage = 'تم الحجز بنجاح! رقم الحجز الخاص بك: ' . $booking->booking_reference;
            }

            // ✅ إعادة التوجيه إلى صفحة النجاح
            return redirect()->route('booking.track.success')
                ->with('success', $successMessage)
                ->with('booking_reference', $booking->booking_reference);
                
        } 
        catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Booking creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['image']) // استبعاد الصورة من اللوج
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage()])
                ->withInput();
        }
    }

}
