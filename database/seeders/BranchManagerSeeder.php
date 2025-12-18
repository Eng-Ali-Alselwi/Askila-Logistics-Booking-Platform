<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BranchManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع الفروع النشطة
        $branches = Branch::where('is_active', true)->get();

        foreach ($branches as $branch) {
            // إنشاء مستخدم مشرف فرع لكل فرع
            $managerEmail = Str::slug($branch->code) . '_manager@askila.com';
            
            $manager = User::firstOrCreate(
                ['email' => $managerEmail],
                [
                    'name' => $branch->manager_name,
                    'email' => $managerEmail,
                    'email_verified_at' => now(),
                    'password' => bcrypt('manager123'), // كلمة مرور افتراضية
                    'phone' => $branch->manager_phone,
                    'is_active' => true,
                    'branch_id' => $branch->id,
                ]
            );

            // تعيين دور مشرف الفرع
            if (!$manager->hasRole(RoleEnum::BRANCH_MANAGER->value)) {
                $manager->assignRole(RoleEnum::BRANCH_MANAGER->value);
            }

            // التأكد من ربط المستخدم بالفرع
            if (!$manager->branch_id) {
                $manager->update(['branch_id' => $branch->id]);
            }

            // set manager_id on branch if empty
            if (!$branch->manager_id) {
                $branch->update(['manager_id' => $manager->id, 'status' => 'active']);
            }

            $this->command->info("تم إنشاء مشرف فرع: {$manager->name} للفرع: {$branch->name}");
        }

        $this->command->info("تم إنشاء " . $branches->count() . " مشرف فرع بنجاح!");
    }
}
