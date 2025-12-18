<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Shipment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tracking_number' => 'ASK-' . Str::upper(Str::random(10)),
            'current_status'  => null,

            'sender_name'     => $this->faker->name(),
            'sender_phone'    => $this->faker->numerify('+9665########'),
            'receiver_name'   => $this->faker->name(),
            'receiver_phone'  => $this->faker->numerify('+2499########'),

            'weight_kg'       => $this->faker->randomFloat(2, 5, 200),
            'volume_cbm'      => $this->faker->randomFloat(3, 0.05, 3.5),
            'declared_value'  => $this->faker->randomFloat(2, 200, 15000),

            'notes'           => $this->faker->optional()->sentence(),
            'shipped_at'      => null,
            'delivered_at'    => null,
        ];
    }
}
