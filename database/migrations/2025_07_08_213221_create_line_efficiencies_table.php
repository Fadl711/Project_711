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
        Schema::create('line_efficiencies', function (Blueprint $table) {
            $table->id();
                        $table->integer('accounting_period_id')->unsigned();

 $table->integer('line_id')->comment('خط الإنتاج');
    $table->date('analysis_date')->comment('تاريخ التحليل');
    $table->enum('time_period', ['shift', 'day', 'week', 'month'])->comment('نوع الفترة');
    $table->decimal('availability', 5, 2)->comment('نسبة التوفر %');
    $table->decimal('performance', 5, 2)->comment('نسبة الأداء %');
    $table->decimal('quality', 5, 2)->comment('نسبة الجودة %');
    $table->decimal('oee', 5, 2)->comment('الكفاءة الكلية للمعدات %');
    $table->decimal('planned_downtime', 8, 2)->comment('وقت التوقف المخطط (ساعات)');
    $table->decimal('unplanned_downtime', 8, 2)->comment('وقت التوقف غير المخطط');
    $table->json('downtime_reasons')->nullable()->comment('أسباب التوقف');
    $table->text('improvement_actions')->nullable()->comment('إجراءات التحسين');
    $table->integer('analyst_id')->comment('محلل الكفاءة');
    $table->integer('reviewed_by')->nullable()->comment('المراجع');
    $table->timestamps();
    $table->index(['line_id', 'analysis_date'])->comment('فهرس للتقارير التاريخية');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_efficiencies');
    }
};
