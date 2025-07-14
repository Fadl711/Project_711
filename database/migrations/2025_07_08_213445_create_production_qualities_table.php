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
        Schema::create('production_qualities', function (Blueprint $table) {
            $table->id();
 $table->integer('production_order_id')->comment('أمر الإنتاج');
    $table->integer('stage_id')->comment('مرحلة الإنتاج');
    $table->decimal('sample_size', 10, 2)->comment('حجم العينة المفحوصة');
    $table->decimal('defect_count', 10, 2)->comment('عدد العيوب المكتشفة');
    $table->decimal('defect_rate', 5, 2)->comment('نسبة العيوب %');
    $table->enum('result', ['passed', 'failed', 'conditional'])->comment('نتيجة الفحص');
    $table->json('measurements')->nullable()->comment('قياسات الجودة');
    $table->text('defect_description')->nullable()->comment('وصف العيوب');
    $table->text('corrective_action')->nullable()->comment('الإجراء التصحيحي');
    $table->integer('inspector_id')->comment('employeesمفتش الجودة');
    $table->integer('approved_by')->nullable()->comment('المدير المعتمد');
    $table->timestamp('inspection_time')->useCurrent()->comment('وقت الفحص');
    $table->timestamps();    
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_qualities');
    }
};
