<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use App\Enums\ShipmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_shipments_index()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $response = $this->actingAs($user)->get('/dashboard/shipments');

        $response->assertStatus(200);
        $response->assertSee('Shipments');
    }

    public function test_can_create_shipment()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $shipmentData = [
            'tracking_number' => 'ASK-123456',
            'sender_name' => 'Test Sender',
            'sender_phone' => '966501234567',
            'receiver_name' => 'Test Receiver',
            'receiver_phone' => '966501234568',
            'weight_kg' => 10.5,
            'volume_cbm' => 0.1,
            'declared_value' => 1000,
            'notes' => 'Test shipment',
        ];

        $response = $this->actingAs($user)->post('/dashboard/shipments', $shipmentData);

        $response->assertRedirect('/dashboard/shipments');
        $this->assertDatabaseHas('shipments', [
            'tracking_number' => 'ASK-123456',
        ]);
    }

    public function test_can_update_shipment_status()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $shipment = Shipment::factory()->create();

        $statusData = [
            'status' => ShipmentStatus::SHIPPED_JED_PORT->value,
            'location_text' => 'Jeddah Port',
            'notes' => 'Shipped from Jeddah Port',
        ];

        $response = $this->actingAs($user)->post("/dashboard/shipments/{$shipment->id}/update-status", $statusData);

        $response->assertRedirect();
        $this->assertDatabaseHas('shipments', [
            'id' => $shipment->id,
            'current_status' => ShipmentStatus::SHIPPED_JED_PORT->value,
        ]);
    }

    public function test_can_view_shipment_details()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $shipment = Shipment::factory()->create();

        $response = $this->actingAs($user)->get("/dashboard/shipments/{$shipment->id}");

        $response->assertStatus(200);
        $response->assertSee($shipment->tracking_number);
    }

    public function test_can_delete_shipment()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $shipment = Shipment::factory()->create();

        $response = $this->actingAs($user)->delete("/dashboard/shipments/{$shipment->id}");

        $response->assertRedirect('/dashboard/shipments');
        $this->assertSoftDeleted('shipments', [
            'id' => $shipment->id,
        ]);
    }
}
