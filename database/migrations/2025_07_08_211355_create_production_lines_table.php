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
        // 1. جدول خطوط الإنتاج (production_lines)

        Schema::create('production_lines', function (Blueprint $table) {
            $table->id();
    $table->string('code', 20)->unique()->nullable()->comment('كود فريد للخط');
    $table->string('name', 100)->comment('اسم خط الإنتاج');
    $table->text('description')->nullable()->comment('وصف الخط');
    $table->integer('department_id')->nullable()->comment('القسم التابع له');
    $table->integer('plant_id')->nullable()->comment('المصنع التابع له');
    $table->enum('automation_level', ['manual', 'semi-auto', 'full-auto'])->comment('مستوى الأتمتة');
    $table->decimal('design_capacity', 10, 2)->comment('السعة التصميمية (وحدة/ساعة)');
    $table->decimal('current_capacity', 10, 2)->comment('السعة الفعلية الحالية');
    $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active')->comment('حالة التشغيل');
    $table->date('commissioning_date')->comment('تاريخ التشغيل الأول');
    $table->date('last_calibration_date')->nullable()->comment('تاريخ آخر معايرة');
    $table->decimal('hourly_operating_cost', 15, 2)->comment('التكلفة التشغيلية للساعة');
    $table->decimal('energy_consumption', 10, 2)->comment('استهلاك الطاقة (ك.و.س/ساعة)');
    $table->json('specifications')->nullable()->comment('مواصفات فنية إضافية');
    $table->json('safety_requirements')->nullable()->comment('متطلبات السلامة');
    $table->integer('created_by')->comment('منشئ السجل');
    $table->integer('updated_by')->nullable()->comment('آخر معدل');
    $table->softDeletes()->comment('حذف ناعم للحفاظ على السجلات');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_lines');
    }
};
