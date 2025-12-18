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
        Schema::table('bookings', function (Blueprint $table) {
            // بيانات جواز السفر
            $table->string('passport_number')->nullable()->after('passenger_id_number');
            $table->date('passport_issue_date')->nullable()->after('passport_number');
            $table->date('passport_expiry_date')->nullable()->after('passport_issue_date');
            $table->string('nationality')->nullable()->after('passport_expiry_date');
            $table->date('date_of_birth')->nullable()->after('nationality');
            $table->string('current_residence_country')->nullable()->after('date_of_birth');
            $table->string('destination_country')->nullable()->after('current_residence_country');
            
            // بيانات الاتصال الإضافية
            $table->string('phone_sudan')->nullable()->after('passenger_phone');
            
            // بيانات الرحلة
            $table->date('travel_date')->nullable()->after('destination_country');
            $table->enum('ticket_type', ['one_way', 'round_trip'])->default('one_way')->after('travel_date');
            $table->string('cabin_type')->nullable()->after('seat_class'); // نوع الكابينة للرحلات البحرية
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'passport_number',
                'passport_issue_date', 
                'passport_expiry_date',
                'nationality',
                'date_of_birth',
                'current_residence_country',
                'destination_country',
                'phone_sudan',
                'travel_date',
                'ticket_type',
                'cabin_type'
            ]);
        });
    }
};
