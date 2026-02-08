<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $query = Flight::active()->upcoming();

        // فلترة حسب نوع الرحلة
        if ($request->filled('trip_type') && $request->trip_type !== 'all') {
            $query->where('trip_type', $request->trip_type);
        }

        // البحث حسب الوجهة
        if ($request->filled('departure') && $request->filled('arrival')) {
            $query->byRoute($request->departure, $request->arrival);
        }

        // البحث حسب التاريخ
        if ($request->filled('departure_date')) {
            $query->byDate($request->departure_date);
        }

        // البحث حسب عدد الركاب
        if ($request->filled('passengers')) {
            $query->where('available_seats', '>=', $request->passengers);
        }

        $flights = $query->orderBy('departure_time')->paginate(12);

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'flights' => $flights->items(),
                'pagination' => [
                    'current_page' => $flights->currentPage(),
                    'last_page' => $flights->lastPage(),
                    'per_page' => $flights->perPage(),
                    'total' => $flights->total(),
                ]
            ]);
        }

        return view('front.flights.index', compact('flights'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'departure' => 'nullable|string',
            'arrival' => 'nullable|string',
            'departure_date' => 'nullable|date|after_or_equal:today',
            'passengers' => 'nullable|integer|min:1|max:9',
            'return_date' => 'nullable|date|after:departure_date'
        ]);

        // بناء استعلام رحلات الذهاب
        $departureFlights = Flight::active()
            ->upcoming()
            ->when($request->departure, function ($q) use ($request) {
                return $q->where('departure_city', $request->departure);
            })
            ->when($request->arrival, function ($q) use ($request) {
                return $q->where('arrival_city', $request->arrival);
            })
            ->when($request->departure_date, function ($q) use ($request) {
                return $q->byDate($request->departure_date);
            })
            ->when($request->passengers, function ($q) use ($request) {
                return $q->where('available_seats', '>=', $request->passengers);
            })
            ->orderBy('departure_time')
            ->paginate(12);

        return view('front.flights.index', ['flights' => $departureFlights]);
    }

    public function show(Flight $flight)
    {
        $flight->load('bookings');
        
        return view('front.flights.show', compact('flight'));
    }

    public function book(Request $request, Flight $flight)
    {
        $request->validate([
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_id_number' => 'nullable|string|max:20',
            'seat_class' => 'required|in:economy,business,first',
            'number_of_passengers' => 'required|integer|min:1|max:9',
            'passenger_details' => 'nullable|array',
            'special_requests' => 'nullable|string|max:1000'
        ]);

        // التحقق من توفر المقاعد
        if (!$flight->canBook($request->number_of_passengers)) {
            return back()->withErrors(['error' => 'لا توجد مقاعد متاحة كافية للرحلة المحددة.']);
        }

        // حساب السعر
        $pricePerSeat = $flight->getPriceForClass($request->seat_class);
        $totalAmount = $pricePerSeat * $request->number_of_passengers;
        $taxAmount = $totalAmount * 0.15; // 15% ضريبة
        $serviceFee = 50; // رسوم خدمة ثابتة
        $finalTotal = $totalAmount + $taxAmount + $serviceFee;

        // إنشاء أو العثور على العميل
        $customer = Customer::firstOrCreate(
            ['email' => $request->passenger_email],
            [
                'name' => $request->passenger_name,
                'phone' => $request->passenger_phone,
                'is_active' => true
            ]
        );

        // إنشاء الحجز
        $booking = Booking::create([
            'flight_id' => $flight->id,
            'customer_id' => $customer->id,
            'passenger_name' => $request->passenger_name,
            'passenger_email' => $request->passenger_email,
            'passenger_phone' => $request->passenger_phone,
            'passenger_id_number' => $request->passenger_id_number,
            'seat_class' => $request->seat_class,
            'number_of_passengers' => $request->number_of_passengers,
            'passenger_details' => $request->passenger_details,
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
            'service_fee' => $serviceFee,
            'special_requests' => $request->special_requests,
            'created_by' => auth()->id() ?? null
        ]);

        // تحديث المقاعد المتاحة
        $flight->updateAvailableSeats(-$request->number_of_passengers);

        return redirect()->route('booking.payment', $booking)
            ->with('success', 'تم إنشاء الحجز بنجاح. يرجى إتمام عملية الدفع.');
    }

    public function track(Request $request)
    {
        $request->validate([
            'booking_reference' => 'required|string'
        ]);

        $booking = Booking::where('booking_reference', $request->booking_reference)
            ->with(['flight', 'customer', 'payments'])
            ->first();

        if (!$booking) {
            return redirect()->route('booking.track')
                ->withErrors(['error' => 'لم يتم العثور على حجز بهذا الرقم المرجعي.'])
                ->withInput();
        }

        return view('front.bookings.track-form', compact('booking'));
    }
}
