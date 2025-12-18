<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_users_index()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $response = $this->actingAs($user)->get('/dashboard/users');

        $response->assertStatus(200);
        $response->assertSee('Users');
    }

    public function test_can_create_user()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $role = Role::create(['name' => 'test_role']);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '966501234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'roles' => [$role->id],
            'is_active' => true,
        ];

        $response = $this->actingAs($user)->post('/dashboard/users', $userData);

        $response->assertRedirect('/dashboard/users');
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $targetUser = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => $targetUser->email,
            'phone' => $targetUser->phone,
        ];

        $response = $this->actingAs($user)->put("/dashboard/users/{$targetUser->id}", $updateData);

        $response->assertRedirect('/dashboard/users');
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $targetUser = User::factory()->create();

        $response = $this->actingAs($user)->delete("/dashboard/users/{$targetUser->id}");

        $response->assertRedirect('/dashboard/users');
        $this->assertSoftDeleted('users', [
            'id' => $targetUser->id,
        ]);
    }

    public function test_cannot_delete_super_admin()
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $response = $this->actingAs($user)->delete("/dashboard/users/{$superAdmin->id}");

        $response->assertRedirect();
        $response->assertSessionHasErrors(['error']);
    }
}
