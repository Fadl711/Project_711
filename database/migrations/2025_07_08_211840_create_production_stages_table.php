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
        //  جدول مراحل الإنتاج
        
        Schema::create('production_stages', function (Blueprint $table) {
            $table->id();
  $table->integer('line_id')->comment('الخط التابع له');
    $table->string('name', 100)->comment('اسم المرحلة');
    $table->integer('sequence')->comment('ترتيب المرحلة في العملية');
    $table->text('purpose')->nullable()->comment('الهدف من المرحلة');
    $table->decimal('standard_duration', 8, 2)->comment('المدة المعيارية بالثواني/وحدة');
    $table->decimal('target_yield', 5, 2)->default(100)->comment('النسبة المستهدفة للجودة %');
    $table->decimal('max_defect_rate', 5, 2)->nullable()->comment('أقصى نسبة عيوب مسموح بها');
    $table->string('required_equipment', 255)->nullable()->comment('أكواد المعدات المطلوبة');
    $table->json('equipment_settings')->nullable()->comment('إعدادات المعدات');
    $table->json('quality_parameters')->nullable()->comment('معايير الجودة');
    $table->text('inspection_instructions')->nullable()->comment('تعليمات الفحص');
    $table->boolean('is_active')->default(true)->comment('هل المرحلة مفعلة؟');
        $table->softDeletes()->comment('حذف ناعم للحفاظ على السجلات');

    $table->timestamps();
    $table->index(['line_id', 'sequence'])->comment('فهرس لتسريع استعلامات الترتيب');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_stages');
    }
};
