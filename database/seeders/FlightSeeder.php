<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        $flights = [
            [
                'flight_number' => 'ASK001',
                'airline' => 'الأسكلة للطيران',
                'aircraft_type' => 'Boeing 737',
                'departure_airport' => 'RUH',
                'arrival_airport' => 'KRT',
                'departure_city' => 'الرياض',
                'arrival_city' => 'الخرطوم',
                'departure_time' => Carbon::tomorrow()->setTime(8, 0),
                'arrival_time' => Carbon::tomorrow()->setTime(11, 30),
                'duration_minutes' => 210,
                'base_price' => 850.00,
                'total_seats' => 180,
                'available_seats' => 150,
                'seat_classes' => ['economy', 'business'],
                'pricing_tiers' => [
                    'economy' => 850.00,
                    'business' => 1500.00
                ],
                'is_active' => true,
                'notes' => 'رحلة يومية مباشرة'
            ],
            [
                'flight_number' => 'ASK002',
                'airline' => 'الأسكلة للطيران',
                'aircraft_type' => 'Airbus A320',
                'departure_airport' => 'JED',
                'arrival_airport' => 'PZU',
                'departure_city' => 'جدة',
                'arrival_city' => 'بورتسودان',
                'departure_time' => Carbon::tomorrow()->setTime(14, 30),
                'arrival_time' => Carbon::tomorrow()->setTime(17, 0),
                'duration_minutes' => 150,
                'base_price' => 750.00,
                'total_seats' => 150,
                'available_seats' => 120,
                'seat_classes' => ['economy'],
                'pricing_tiers' => [
                    'economy' => 750.00
                ],
                'is_active' => true,
                'notes' => null
            ],
            [
                'flight_number' => 'ASK003',
                'airline' => 'الأسكلة للطيران',
                'aircraft_type' => 'Boeing 737',
                'departure_airport' => 'DMM',
                'arrival_airport' => 'KSL',
                'departure_city' => 'الدمام',
                'arrival_city' => 'كسلا',
                'departure_time' => Carbon::tomorrow()->addDay()->setTime(10, 15),
                'arrival_time' => Carbon::tomorrow()->addDay()->setTime(13, 45),
                'duration_minutes' => 210,
                'base_price' => 900.00,
                'total_seats' => 180,
                'available_seats' => 160,
                'seat_classes' => ['economy', 'business'],
                'pricing_tiers' => [
                    'economy' => 900.00,
                    'business' => 1600.00
                ],
                'is_active' => true,
                'notes' => null
            ],
            [
                'flight_number' => 'ASK004',
                'airline' => 'الأسكلة للطيران',
                'aircraft_type' => 'Airbus A320',
                'departure_airport' => 'MED',
                'arrival_airport' => 'EDB',
                'departure_city' => 'المدينة المنورة',
                'arrival_city' => 'نيالا',
                'departure_time' => Carbon::tomorrow()->addDays(2)->setTime(16, 0),
                'arrival_time' => Carbon::tomorrow()->addDays(2)->setTime(19, 30),
                'duration_minutes' => 210,
                'base_price' => 800.00,
                'total_seats' => 150,
                'available_seats' => 140,
                'seat_classes' => ['economy'],
                'pricing_tiers' => [
                    'economy' => 800.00
                ],
                'is_active' => true,
                'notes' => 'رحلة أسبوعية'
            ],
            [
                'flight_number' => 'ASK005',
                'airline' => 'الأسكلة للطيران',
                'aircraft_type' => 'Boeing 737',
                'departure_airport' => 'TUU',
                'arrival_airport' => 'ELF',
                'departure_city' => 'تبوك',
                'arrival_city' => 'الفاشر',
                'departure_time' => Carbon::tomorrow()->addDays(3)->setTime(12, 30),
                'arrival_time' => Carbon::tomorrow()->addDays(3)->setTime(16, 0),
                'duration_minutes' => 210,
                'base_price' => 950.00,
                'total_seats' => 180,
                'available_seats' => 170,
                'seat_classes' => ['economy', 'business'],
                'pricing_tiers' => [
                    'economy' => 950.00,
                    'business' => 1700.00
                ],
                'is_active' => true,
                'notes' => null
            ]
        ];

        foreach ($flights as $flightData) {
            Flight::firstOrCreate(
                ['flight_number' => $flightData['flight_number']], // البحث برقم الرحلة
                $flightData // البيانات للإدراج
            );
        }
    }
}
