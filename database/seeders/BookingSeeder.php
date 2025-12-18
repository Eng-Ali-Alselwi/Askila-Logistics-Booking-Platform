<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $flights = Flight::all();
        $customers = Customer::all();

        if ($flights->isEmpty() || $customers->isEmpty()) {
            $this->command->info('يجب تشغيل FlightSeeder و CustomerSeeder أولاً');
            return;
        }

        $bookings = [
            [
                'flight_id' => $flights->first()->id,
                'customer_id' => $customers->first()->id,
                'passenger_name' => 'أحمد محمد عبدالله',
                'passenger_email' => 'ahmed.mohammed@example.com',
                'passenger_phone' => '966501234567',
                'passenger_id_number' => '1234567890',
                'image' => '/bookings/sudani___.jpg',
                'seat_class' => 'economy',
                'number_of_passengers' => 1,
                'total_amount' => 850.00,
                'tax_amount' => 127.50,
                'service_fee' => 50.00,
                'currency' => 'SAR',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => 'mada',
                'payment_date' => now(),
                'special_requests' => 'مقعد بجانب النافذة'
            ],
            [
                'flight_id' => $flights->skip(1)->first()->id,
                'customer_id' => $customers->skip(1)->first()->id,
                'passenger_name' => 'فاطمة أحمد علي',
                'passenger_email' => 'fatima.ahmed@example.com',
                'passenger_phone' => '966509876543',
                'passenger_id_number' => '9876543210',
                'image' => '/bookings/sudani___.jpg',
                'seat_class' => 'economy',
                'number_of_passengers' => 2,
                'total_amount' => 1500.00,
                'tax_amount' => 225.00,
                'service_fee' => 100.00,
                'currency' => 'SAR',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => null,
                'payment_date' => null,
                'special_requests' => 'مقاعد متجاورة'
            ],
            [
                'flight_id' => $flights->skip(2)->first()->id,
                'customer_id' => $customers->skip(2)->first()->id,
                'passenger_name' => 'محمد عبدالرحمن',
                'passenger_email' => 'mohammed.abdelrahman@example.com',
                'passenger_phone' => '966555123456',
                'passenger_id_number' => '5555123456',
                'image' => '/bookings/sudani___.jpg',
                'seat_class' => 'business',
                'number_of_passengers' => 1,
                'total_amount' => 1600.00,
                'tax_amount' => 240.00,
                'service_fee' => 50.00,
                'currency' => 'SAR',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_method' => 'visa',
                'payment_date' => now()->subHours(2),
                'special_requests' => null
            ],
            [
                'flight_id' => $flights->skip(3)->first()->id,
                'customer_id' => $customers->skip(3)->first()->id,
                'passenger_name' => 'عائشة محمد',
                'passenger_email' => 'aisha.mohammed@example.com',
                'passenger_phone' => '966544321098',
                'passenger_id_number' => '4444321098',
                'image' => '/bookings/sudani___.jpg',
                'seat_class' => 'economy',
                'number_of_passengers' => 3,
                'total_amount' => 2400.00,
                'tax_amount' => 360.00,
                'service_fee' => 150.00,
                'currency' => 'SAR',
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'payment_method' => 'mastercard',
                'payment_date' => now()->subDays(1),
                'special_requests' => 'وجبة حلال',
                'cancellation_reason' => 'تغيير في خطة السفر',
                'cancelled_at' => now()->subHours(5)
            ]
        ];

        foreach ($bookings as $bookingData) {
            Booking::firstOrCreate(
                [
                    'flight_id' => $bookingData['flight_id'],
                    'customer_id' => $bookingData['customer_id'],
                    'passenger_email' => $bookingData['passenger_email']
                ], // البحث بالرحلة والعميل والبريد
                $bookingData // البيانات للإدراج
            );
        }
    }
}
