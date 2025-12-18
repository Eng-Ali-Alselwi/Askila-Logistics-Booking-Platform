<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {

            $table->ulid('id')->primary();

            $table->foreignUlid('shipment_id')
                ->constrained('shipments')
                ->cascadeOnDelete();

            $table->string('status');

            // وقت حدوث الحدث
            $table->timestamp('happened_at')->useCurrent();

            // مكان أو وصف حر للحالة (بديل بسيط لـ branches)
            $table->string('location_text')->nullable();

            // ملاحظات إضافية (سبب التأخير، رقم بوليصة، الخ)
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();


            $table->timestamps();
            $table->index(['shipment_id', 'happened_at','created_by']);
            // $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
