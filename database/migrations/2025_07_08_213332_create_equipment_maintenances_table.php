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
        Schema::create('equipment_maintenances', function (Blueprint $table) {
            $table->id();
 $table->foreignId('line_id')->constrained('production_lines')->comment('خط الإنتاج');
    $table->string('equipment_code')->comment('كود المعدة');
    $table->enum('maintenance_type', ['preventive', 'corrective', 'predictive', 'breakdown'])->comment('نوع الصيانة');
    $table->string('maintenance_code')->nullable()->comment('كود إجراء الصيانة');
    $table->text('description')->comment('وصف العملية');
    $table->dateTime('scheduled_date')->comment('التاريخ المخطط');
    $table->dateTime('start_time')->nullable()->comment('البدء الفعلي');
    $table->dateTime('end_time')->nullable()->comment('الانتهاء الفعلي');
    $table->decimal('estimated_cost', 12, 2)->comment('التكلفة التقديرية');
    $table->decimal('actual_cost', 12, 2)->nullable()->comment('التكلفة الفعلية');
    $table->integer('technician_id')->comment('employeesالفني المسؤول');
    $table->text('parts_replaced')->nullable()->comment('الأجزاء المستبدلة');
    $table->enum('status', ['scheduled', 'in_progress', 'completed', 'canceled'])->default('scheduled')->comment('حالة الصيانة');
    $table->decimal('downtime_hours', 8, 2)->nullable()->comment('ساعات التوقف');
    $table->text('notes')->nullable()->comment('ملاحظات');
    $table->integer('approved_by')->nullable()->comment('المعتمد');
    $table->integer('verified_by')->nullable()->comment('المدقق');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenances');
    }
};
