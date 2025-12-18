<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تحديث enum payment_method لتشمل PayPal
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('credit_card', 'debit_card', 'bank_transfer', 'apple_pay', 'mada', 'visa', 'mastercard', 'paypal') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum payment_method إلى حالته السابقة
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('credit_card', 'debit_card', 'bank_transfer', 'apple_pay', 'mada', 'visa', 'mastercard') NOT NULL");
    }
};
