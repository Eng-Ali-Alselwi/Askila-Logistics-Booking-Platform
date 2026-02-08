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
                    'happened_at' => (clone $base)->addHours($i * 12),
                    'location_text' => $status->label(),
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
