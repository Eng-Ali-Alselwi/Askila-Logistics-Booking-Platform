<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'أحمد محمد علي',
                'phone' => '966501234567',
                'email' => 'ahmed@example.com',
                'city' => 'الرياض',
                'address' => 'حي النرجس، شارع الملك فهد',
                'national_id' => '1234567890',
                'is_active' => true,
            ],
            [
                'name' => 'فاطمة عبدالله',
                'phone' => '966501234568',
                'email' => 'fatima@example.com',
                'city' => 'جدة',
                'address' => 'حي الروضة، شارع التحلية',
                'national_id' => '1234567891',
                'is_active' => true,
            ],
            [
                'name' => 'محمد حسن',
                'phone' => '966501234569',
                'email' => 'mohammed@example.com',
                'city' => 'الدمام',
                'address' => 'حي الفيصلية، شارع الملك عبدالعزيز',
                'national_id' => '1234567892',
                'is_active' => true,
            ],
            [
                'name' => 'عائشة أحمد',
                'phone' => '966501234570',
                'email' => 'aisha@example.com',
                'city' => 'الرياض',
                'address' => 'حي العليا، شارع العليا',
                'national_id' => '1234567893',
                'is_active' => false,
            ],
            [
                'name' => 'عبدالرحمن محمد',
                'phone' => '966501234571',
                'email' => 'abdulrahman@example.com',
                'city' => 'جدة',
                'address' => 'حي الزهراء، شارع الأمير سلطان',
                'national_id' => '1234567894',
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(
                ['phone' => $customer['phone']], // البحث بالهاتف
                $customer // البيانات للإدراج
            );
        }
    }
}
