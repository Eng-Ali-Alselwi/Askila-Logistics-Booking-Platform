<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing permissions and roles
        Permission::query()->delete();
        Role::query()->delete();

        // Create all permissions based on your detailed requirements
        $permissions = [
            // User Management Permissions
            'create users',
            'view users',
            'manage users',
            'edit users',
            'activate users',
            'delete users',
            
            // Role Management Permissions
            'create roles',
            'view roles',
            'manage roles',
            'delete roles',
            
            // Shipment Management Permissions
            'manage shipments',
            'edit shipments',
            'create shipments',
            'view shipments',
            'delete shipments',
            'export shipments',
            'update shipment status',
            'add shipments',
            
            // Customer Management Permissions
            'create customers',
            'view customers',
            'manage customers',
            'edit customers',
            'delete customers',
            
            // Branch Management Permissions
            'manage branches',
            'edit branches',
            'create branches',
            'view branches',
            'delete branches',
            'manage branch',
            
            // Reports Permissions
            'export reports',
            'view reports',
            'view general reports',
            'view branch reports',
            
            // Booking Management Permissions
            'manage bookings',
            
            // Flight Management Permissions
            'manage flights',
            
            // Settings Permissions
            'manage settings',
            'view settings',
        ];

        // Ensure unique list and create idempotently
        $permissions = array_values(array_unique($permissions));
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions according to your detailed requirements
        
        // 👑 Super Admin - All permissions
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // 💼 Manager - Most permissions except user deletion
        $manager = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'create users',
            'view users',
            'manage users',
            'edit users',
            'activate users',
            // 'delete users', // Excluded as per requirements
            'manage shipments',
            'edit shipments',
            'create shipments',
            'view shipments',
            'delete shipments',
            'export shipments',
            'update shipment status',
            'add shipments',
            'create customers',
            'view customers',
            'manage customers',
            'edit customers',
            'delete customers',
            'manage branches',
            'edit branches',
            'create branches',
            'view branches',
            'delete branches',
            'export reports',
            'view reports',
            'manage bookings',
            'manage flights',
        ]);

        // 🏢 Branch Manager - Limited permissions
        $branchManager = Role::create(['name' => 'branch_manager', 'guard_name' => 'web']);
        $branchManager->givePermissionTo([
            'update shipment status',
            'create shipments',
            'view shipments',
            'add shipments',
            'create customers',
            'view customers',
            'manage customers',
            'edit customers',
            'view branches',
            'manage branch',
            'view general reports',
            'view branch reports',
            'manage bookings',
            'manage flights',
        ]);

        // 🙋 Customer Service - Customer focused
        $customerService = Role::create(['name' => 'customer_service', 'guard_name' => 'web']);
        $customerService->givePermissionTo([
            'create customers',
            'view customers',
            'edit customers',
            'view shipments',
        ]);

        // 📦 Sender - Shipment creation only
        $sender = Role::create(['name' => 'sender', 'guard_name' => 'web']);
        $sender->givePermissionTo([
            'add shipments',
            'create shipments',
            'view shipments',
        ]);

        // 🔄 Updater - Status updates only
        $updater = Role::create(['name' => 'updater', 'guard_name' => 'web']);
        $updater->givePermissionTo([
            'update shipment status',
            'view shipments',
            'view reports',
        ]);

        // 🔎 Viewer - Read only access
        $viewer = Role::create(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->givePermissionTo([
            'view branches',
            'view customers',
            'view shipments',
        ]);

        $this->command->info('✅ Roles and permissions system created successfully!');
        $this->command->info('📊 Created ' . count($permissions) . ' permissions');
        $this->command->info('👥 Created 7 roles with specific permissions');
    }
}