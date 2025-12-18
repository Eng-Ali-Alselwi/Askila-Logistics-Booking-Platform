<?php

namespace App\Http\Controllers\Dashboard\Flights;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $query = Flight::query();

        // البحث والفلترة
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('flight_number', 'like', '%' . $request->search . '%')
                  ->orWhere('airline', 'like', '%' . $request->search . '%')
                  ->orWhere('departure_city', 'like', '%' . $request->search . '%')
                  ->orWhere('arrival_city', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('departure_time', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('departure_time', '<=', $request->date_to);
        }

        $flights = $query->orderBy('departure_time', 'desc')->paginate(15);

        return view('dashboard.flights.index', compact('flights'));
    }

    public function create()
    {
        $flight = null;
        return view('dashboard.flights.create', compact('flight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flight_number' => 'required|string|max:20|unique:flights',
            'airline' => 'required|string|max:100',
            'aircraft_type' => 'nullable|string|max:50',
            'departure_airport' => 'required|string|max:10',
            'arrival_airport' => 'required|string|max:10',
            'departure_city' => 'required|string|max:100',
            'arrival_city' => 'required|string|max:100',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'base_price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1|max:1000',
            'seat_classes' => 'nullable|array',
            'pricing_tiers' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        // حساب مدة الرحلة
        $departure = \Carbon\Carbon::parse($request->departure_time);
        $arrival = \Carbon\Carbon::parse($request->arrival_time);
        $durationMinutes = $departure->diffInMinutes($arrival);

        Flight::create([
           'flight_number' => $request->flight_number,
            'airline' => $request->airline,
            'aircraft_type' => $request->aircraft_type,
            'departure_airport' => $request->departure_airport,
            'arrival_airport' => $request->arrival_airport,
            'departure_city' => $request->departure_city,
            'arrival_city' => $request->arrival_city,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'duration_minutes' => $durationMinutes,
            'base_price' => $request->base_price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats,
            'seat_classes' => $request->seat_classes ?? ['economy'],
            'pricing_tiers' => $request->pricing_tiers ?? ['economy' => $request->base_price],
            'is_active' => $request->boolean('is_active'),
            'notes' => $request->notes
        ]);

        return redirect()->route('dashboard.flights.index')
            ->with('success', 'تم إنشاء الرحلة بنجاح.');
    }

    public function show(Flight $flight)
    {
        $flight->load(['bookings.customer', 'bookings.payments']);
        
        return view('dashboard.flights.show', compact('flight'));
    }

    public function edit(Flight $flight)
    {
        return view('dashboard.flights.create', compact('flight'));
    }

    public function update(Request $request, Flight $flight)
    {
        $request->validate([
            'flight_number' => 'required|string|max:20|unique:flights,flight_number,' . $flight->id,
            'airline' => 'required|string|max:100',
            'aircraft_type' => 'nullable|string|max:50',
            'departure_airport' => 'required|string|max:10',
            'arrival_airport' => 'required|string|max:10',
            'departure_city' => 'required|string|max:100',
            'arrival_city' => 'required|string|max:100',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'base_price' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1|max:1000',
            'seat_classes' => 'nullable|array',
            'pricing_tiers' => 'nullable|array',
            'notes' => 'nullable|string|max:1000'
        ]);

        // حساب مدة الرحلة
        $departure = \Carbon\Carbon::parse($request->departure_time);
        $arrival = \Carbon\Carbon::parse($request->arrival_time);
        $durationMinutes = $departure->diffInMinutes($arrival);

        // التحقق من أن عدد المقاعد الجديد لا يقل عن المقاعد المحجوزة
        $bookedSeats = $flight->total_seats - $flight->available_seats;
        if ($request->total_seats < $bookedSeats) {
            return back()->withErrors(['total_seats' => 'لا يمكن تقليل عدد المقاعد إلى أقل من المقاعد المحجوزة (' . $bookedSeats . ').']);
        }

        $flight->update([
            'flight_number' => $request->flight_number,
            'airline' => $request->airline,
            'aircraft_type' => $request->aircraft_type,
            'departure_airport' => $request->departure_airport,
            'arrival_airport' => $request->arrival_airport,
            'departure_city' => $request->departure_city,
            'arrival_city' => $request->arrival_city,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'duration_minutes' => $durationMinutes,
            'base_price' => $request->base_price,
            'total_seats' => $request->total_seats,
            'available_seats' => $request->total_seats - $bookedSeats,
            'seat_classes' => $request->seat_classes ?? ['economy'],
            'pricing_tiers' => $request->pricing_tiers ?? ['economy' => $request->base_price],
            'is_active' => $request->boolean('is_active'),
            'notes' => $request->notes
        ]);

        return redirect()->route('dashboard.flights.index')
            ->with('success', 'تم تحديث الرحلة بنجاح.');
    }

    public function destroy(Flight $flight)
    {
        // التحقق من وجود حجوزات
        if ($flight->bookings()->exists()) {
            return back()->withErrors(['error' => 'لا يمكن حذف رحلة تحتوي على حجوزات.']);
        }

        $flight->delete();

        return redirect()->route('dashboard.flights.index')
            ->with('success', 'تم حذف الرحلة بنجاح.');
    }

    public function toggleStatus(Flight $flight)
    {
        $flight->update(['is_active' => !$flight->is_active]);

        $status = $flight->is_active ? 'تفعيل' : 'إلغاء تفعيل';
        
        return back()->with('success', "تم {$status} الرحلة بنجاح.");
    }
}
