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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique();
            $table->string('airline');
            $table->string('aircraft_type')->nullable();
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->string('departure_city');
            $table->string('arrival_city');
            $table->datetime('departure_time');
            $table->datetime('arrival_time');
            $table->integer('duration_minutes');
            $table->decimal('base_price', 10, 2);
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->json('seat_classes')->nullable(); // Economy, Business, First
            $table->json('pricing_tiers')->nullable(); // Different prices for different classes
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
