<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ تعديل عمود status ليشمل الحالات الجديدة temporary و pending_confirmation
        DB::statement("
            ALTER TABLE bookings 
            MODIFY COLUMN status 
            ENUM('pending', 'confirmed', 'cancelled', 'completed', 'temporary', 'pending_confirmation') 
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // ⬅️ إعادة الحالة إلى الشكل السابق (بدون temporary و pending_confirmation)
        DB::statement("
            ALTER TABLE bookings 
            MODIFY COLUMN status 
            ENUM('pending', 'confirmed', 'cancelled', 'completed') 
            NOT NULL DEFAULT 'pending'
        ");
    }
};