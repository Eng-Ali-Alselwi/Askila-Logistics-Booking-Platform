<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                if (!Schema::hasColumn('branches', 'manager_id')) {
                    $table->foreignId('manager_id')->nullable()->after('email')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('branches', 'status')) {
                    $table->string('status')->default('active')->after('is_active');
                    $table->index('status');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                if (Schema::hasColumn('branches', 'manager_id')) {
                    $table->dropForeign(['manager_id']);
                    $table->dropColumn('manager_id');
                }
                if (Schema::hasColumn('branches', 'status')) {
                    $table->dropIndex(['status']);
                    $table->dropColumn('status');
                }
            });
        }
    }
};


