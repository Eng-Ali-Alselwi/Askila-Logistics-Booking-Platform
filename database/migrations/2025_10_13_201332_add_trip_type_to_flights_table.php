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
        Schema::table('flights', function (Blueprint $table) {
            $table->enum('trip_type', ['air', 'land', 'sea'])->default('air')->after('flight_number');
            $table->string('vehicle_type')->nullable()->after('trip_type'); // نوع المركبة/الطائرة/السفينة
            $table->string('operator_name')->nullable()->after('vehicle_type'); // اسم الشركة المشغلة
            $table->string('departure_terminal')->nullable()->after('departure_airport'); // محطة المغادرة
            $table->string('arrival_terminal')->nullable()->after('arrival_airport'); // محطة الوصول
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn(['trip_type', 'vehicle_type', 'operator_name', 'departure_terminal', 'arrival_terminal']);
        });
    }
};
