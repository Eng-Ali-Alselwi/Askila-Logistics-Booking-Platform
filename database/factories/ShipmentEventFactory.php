<?php

namespace Database\Factories;

use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShipmentEvent>
 */
class ShipmentEventFactory extends Factory
{
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shipment_id'   => Shipment::factory(),
            'status'        => 'received_at_branch',
            'happened_at'   => now(),
            'location_text' => $this->faker->randomElement([
                'فرع جدة', 'مستودع جدة', 'ميناء جدة الإسلامي', 'ميناء عثمان دقنة', 'فرع الاستلام'
            ]),
            'notes'         => $this->faker->optional()->sentence(8),
        ];
    }
}
