<?php

namespace Database\Seeders;

use App\Actions\Shipments\RecordShipmentEvent;
use App\Enums\ShipmentStatus;
use App\Models\Shipment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;


class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $record = app(RecordShipmentEvent::class);

        // أنشئ 5 شحنات بتيار حالات منطقي
        Shipment::factory(5)->create()->each(function(Shipment $shipment) use ($record) {
            $base = Carbon::now()->subDays(rand(2, 12))->startOfDay();
            $timeline = ShipmentStatus::timeline();

            foreach ($timeline as $i => $status) {
                $record->handle($shipment, $status, [
                    'happened_at'   => (clone $base)->addHours($i * rand(6, 18)),
                    'location_text' => match ($status) {
                        ShipmentStatus::CREATED                     => 'تم إدخال الشحنة في النظام',
                        ShipmentStatus::RECEIVED_AT_BRANCH          => 'فرع جدة',
                        ShipmentStatus::IN_TRANSIT                  => 'في الطريق',
                        ShipmentStatus::ARRIVED_JED_WAREHOUSE       => 'مستودع جدة',
                        ShipmentStatus::SHIPPED_JED_PORT            => 'ميناء جدة الإسلامي',
                        ShipmentStatus::ARRIVED_SUDAN_PORT          => 'ميناء عثمان دقنة',
                        ShipmentStatus::ARRIVED_DESTINATION_BRANCH  => 'فرع الاستلام-بورتسودان',
                        ShipmentStatus::READY_FOR_DELIVERY          => 'جاهزة للتسليم',
                        ShipmentStatus::DELIVERED                   => 'تم التسليم',
                    },
                    'notes' => Arr::random([
                        null,
                        'تم التحقق من الوثائق.',
                        'تم التحميل على الناقلة.',
                        'تأخير بسيط بسبب ازدحام الميناء.',
                        'تم الاتصال بالمستلم للتنسيق.',
                    ]),
                ]);
            }
        });

        // شحنات "قيد الشحن" ولم تُسلّم بعد (تنوع واقعي)
        Shipment::factory(3)->create()->each(function(Shipment $shipment) use ($record) {
            $base = now()->subDays(rand(1, 6));
            $upto  = rand(2, 4); // توقف قبل الوصول/التسليم
            $timeline = array_slice(ShipmentStatus::timeline(), 0, $upto);

            foreach ($timeline as $i => $status) {
                $record->handle($shipment, $status, [
                    'happened_at'   => (clone $base)->addHours(8 * $i),
                    'location_text' => '—',
                ]);
            }
        });
    }
}
