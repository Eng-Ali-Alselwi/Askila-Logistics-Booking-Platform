<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolePermissionSeeder::class,
            CustomerSeeder::class,
            BranchSeeder::class,
            BranchManagerSeeder::class,
        ]);
        // إنشاء المستخدمين (آمن - لا يضيف إذا كانوا موجودين)
        $super_admin = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'phone' => '0500417859',
                'is_active' => true,
            ]
        );
        $super_admin->assignRole('super_admin');

        $manager = User::firstOrCreate(
            ['email' => 'admin@askla.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@askla.com',
                'email_verified_at' => now(),
                'password' => bcrypt('admin123'),
                'phone' => '1234567890',
                'is_active' => true,
            ]
        );
        $manager->assignRole('manager');

        // create sample users per branch with specific roles
        $branches = \App\Models\Branch::all();
        foreach ($branches as $branch) {
            $seedBase = (int)($branch->id);
            // customer_service user
            $cs = User::firstOrCreate(
                ['email' => strtolower($branch->code).'_cs@askila.com'],
                [
                    'name' => $branch->name.' CS',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'phone' => $this->generateUniquePhone($seedBase * 10 + 1),
                    'is_active' => true,
                    'branch_id' => $branch->id,
                ]
            );
            if (!$cs->hasRole('customer_service')) { $cs->assignRole('customer_service'); }

            // sender user
            $sender = User::firstOrCreate(
                ['email' => strtolower($branch->code).'_sender@askila.com'],
                [
                    'name' => $branch->name.' Sender',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'phone' => $this->generateUniquePhone($seedBase * 10 + 2),
                    'is_active' => true,
                    'branch_id' => $branch->id,
                ]
            );
            if (!$sender->hasRole('sender')) { $sender->assignRole('sender'); }

            // updater user
            $updater = User::firstOrCreate(
                ['email' => strtolower($branch->code).'_updater@askila.com'],
                [
                    'name' => $branch->name.' Updater',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'phone' => $this->generateUniquePhone($seedBase * 10 + 3),
                    'is_active' => true,
                    'branch_id' => $branch->id,
                ]
            );
            if (!$updater->hasRole('updater')) { $updater->assignRole('updater'); }

            // viewer user
            $viewer = User::firstOrCreate(
                ['email' => strtolower($branch->code).'_viewer@askila.com'],
                [
                    'name' => $branch->name.' Viewer',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'phone' => $this->generateUniquePhone($seedBase * 10 + 4),
                    'is_active' => true,
                    'branch_id' => $branch->id,
                ]
            );
            if (!$viewer->hasRole('viewer')) { $viewer->assignRole('viewer'); }
        }

        // Akram super admin user (requested)
        $akram = User::firstOrCreate(
            ['email' => 'akram@example.com'],
            [
                'name' => 'أكرم',
                'email' => 'akram@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('00000000'),
                'phone' => '0500000010',
                'is_active' => true,
            ]
        );
        if (! $akram->hasRole('super_admin')) {
            $akram->assignRole('super_admin');
        }

        // إنشاء مستخدمين تجريبيين (آمن)
        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create();
            if (!$user->hasRole('updater')) {
                $user->assignRole('updater');
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $user = User::factory()->create();
            if (!$user->hasRole('sender')) {
                $user->assignRole('sender');
            }
        }

            $this->call([
                ShipmentSeeder::class,
                FlightSeeder::class,
                BookingSeeder::class,
            ]);
    }

    private function generateUniquePhone(int $seed): string
    {
        // Generate a Saudi-like mobile starting with 05 and ensure uniqueness
        // Try deterministic then fallback to random suffix if taken
        $prefix = '05' . str_pad((string)($seed % 1000), 3, '0', STR_PAD_LEFT);
        $suffix = str_pad((string)($seed % 10000), 4, '0', STR_PAD_LEFT);
        $phone = $prefix . $suffix;
        $attempts = 0;
        while (\App\Models\User::where('phone', $phone)->exists() && $attempts < 50) {
            $suffix = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $phone = $prefix . $suffix;
            $attempts++;
        }
        // As last resort, append a random digit to reach 12 if needed
        if (\App\Models\User::where('phone', $phone)->exists()) {
            $phone = $phone . (string)random_int(0,9);
        }
        return $phone;
    }
}
