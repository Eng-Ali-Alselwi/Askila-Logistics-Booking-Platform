<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'name' => 'الفرع الرئيسي - الرياض',
                'code' => 'RYD001',
                'city' => 'الرياض',
                'address' => 'شارع الملك فهد، حي العليا',
                'phone' => '966112345678',
                'whatsapp_phone' => '+967772674547',
                'email' => 'riyadh@askila.com',
                'manager_name' => 'أحمد محمد السعيد',
                'manager_phone' => '966501234567',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'is_active' => true,
            ],
            [
                'name' => 'فرع جدة - الميناء',
                'code' => 'JED001',
                'city' => 'جدة',
                'address' => 'ميناء جدة الإسلامي، منطقة الميناء',
                'phone' => '966126543210',
                'whatsapp_phone' => '+967772674547',
                'email' => 'jeddah@askila.com',
                'manager_name' => 'محمد عبدالله البحر',
                'manager_phone' => '966501234568',
                'latitude' => 21.4858,
                'longitude' => 39.1925,
                'is_active' => true,
            ],
            [
                'name' => 'فرع الدمام - الخبر',
                'code' => 'DMM001',
                'city' => 'الدمام',
                'address' => 'شارع الملك عبدالعزيز، حي الفيصلية',
                'phone' => '966133456789',
                'whatsapp_phone' => '+967772674547',
                'email' => 'dammam@askila.com',
                'manager_name' => 'عبدالرحمن أحمد الشرقاوي',
                'manager_phone' => '966501234569',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
                'is_active' => true,
            ],
            [
                'name' => 'فرع الخرطوم - السودان',
                'code' => 'KRT001',
                'city' => 'الخرطوم',
                'address' => 'شارع الجامعة، حي السوق العربي',
                'phone' => '249123456789',
                'whatsapp_phone' => '+967772674547',
                'email' => 'khartoum@askila.com',
                'manager_name' => 'عبدالله محمد النور',
                'manager_phone' => '249912345678',
                'latitude' => 15.5007,
                'longitude' => 32.5599,
                'is_active' => true,
            ],
            [
                'name' => 'فرع بورتسودان - السودان',
                'code' => 'PTS001',
                'city' => 'بورتسودان',
                'address' => 'ميناء بورتسودان، منطقة الميناء',
                'phone' => '249123456790',
                'whatsapp_phone' => '+967772674547',
                'email' => 'portsudan@askila.com',
                'manager_name' => 'حسن علي البحر',
                'manager_phone' => '249912345679',
                'latitude' => 19.6158,
                'longitude' => 37.2164,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(
                ['code' => $branch['code']], // البحث بالكود
                $branch // البيانات للإدراج
            );
        }
    }
}
