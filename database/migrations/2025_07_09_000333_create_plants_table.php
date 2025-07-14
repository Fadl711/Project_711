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
        Schema::create('plants', function (Blueprint $table) {
              $table->id();
            $table->string('code', 20)->unique()->comment('كود المصنع');
            $table->string('name', 100)->comment('اسم المصنع');
            $table->string('location')->comment('الموقع الجغرافي');
            $table->decimal('area', 10, 2)->comment('المساحة بالمتر المربع');
            $table->date('establishment_date')->comment('تاريخ الإنشاء');
            $table->enum('status', ['active', 'inactive', 'under_construction'])->default('active')->comment('حالة المصنع');
            $table->integer('employee_count')->default(0)->comment('عدد الموظفين');
            $table->decimal('annual_production_capacity', 15, 2)->comment('القدرة الإنتاجية السنوية');
            $table->text('description')->nullable()->comment('وصف المصنع');
            $table->json('facilities')->nullable()->comment('المرافق المتاحة');
            $table->integer('created_by')->comment('منشئ السجل');
            $table->integer('updated_by')->nullable()->comment('آخر معدل');
            $table->softDeletes()->comment('حذف ناعم');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
