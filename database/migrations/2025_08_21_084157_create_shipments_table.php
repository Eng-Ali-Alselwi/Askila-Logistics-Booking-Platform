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
        Schema::create('shipments', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->string('tracking_number', 40)->unique();

            // كاش للحالة الحالية (مصدر الحقيقة يكون في shipment_events)
            $table->string('current_status')->nullable()->index();

            // حقول اختيارية
            $table->string('sender_name')->nullable();
            $table->string('sender_phone', 20)->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone', 20)->nullable();

            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->decimal('volume_cbm', 8, 3)->nullable();
            $table->decimal('declared_value', 12, 2)->nullable();
            // volume_cbm (الحجم بالمتر المكعب): مهم جدًا في الشحن البحري لأن التسعير غالبًا يتم بالحجم (LCL/FCL)، ويؤثر على الحجز، التكديس، والتكلفة. وجوده من البداية يسهّل التسعير والتقارير.
            // declared_value (القيمة المصرّح بها): تُستخدم لـ التخليص الجمركي، تقييم التأمين والمسؤولية، وحالات التعويض عند التلف/الفقد. كذلك قد تدخل في حسابات الضرائب/الرسوم.

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            // إضافة الأعمدة المطلوبة
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('destination_branch_id')->nullable()->constrained('branches')->nullOnDelete();

            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // فهرس مفيد لاستعلامات لوحة التحكم الحديثة
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
