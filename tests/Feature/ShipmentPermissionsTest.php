<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShipmentPermissionsTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed the roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    /** @test */
    public function manager_can_access_all_shipment_features()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $this->actingAs($user);

        // Test index page access
        $response = $this->get(route('dashboard.shipments.index'));
        $response->assertStatus(200);

        // Test create page access
        $response = $this->get(route('dashboard.shipments.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_service_can_only_view_shipments()
    {
        $user = User::factory()->create();
        $user->assignRole('customer_service');

        $this->actingAs($user);

        // Test index page access
        $response = $this->get(route('dashboard.shipments.index'));
        $response->assertStatus(200);

        // Test create page access - should be forbidden
        $response = $this->get(route('dashboard.shipments.create'));
        $response->assertStatus(403);
    }

    /** @test */
    public function viewer_can_only_view_shipments()
    {
        $user = User::factory()->create();
        $user->assignRole('viewer');

        $this->actingAs($user);

        // Test index page access
        $response = $this->get(route('dashboard.shipments.index'));
        $response->assertStatus(200);

        // Test create page access - should be forbidden
        $response = $this->get(route('dashboard.shipments.create'));
        $response->assertStatus(403);
    }
}