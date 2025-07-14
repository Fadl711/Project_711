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
               Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('كود القسم');
            $table->string('name', 100)->comment('اسم القسم');
            $table->integer('plant_id')->comment('المصنع التابع له');
            $table->string('manager_name')->nullable()->comment('اسم المدير');
            $table->string('phone')->nullable()->comment('هاتف القسم');
            $table->string('email')->nullable()->comment('البريد الإلكتروني');
            $table->enum('type', ['production', 'maintenance', 'quality', 'logistics', 'admin'])->comment('نوع القسم');
            $table->integer('employee_count')->default(0)->comment('عدد الموظفين');
            $table->decimal('budget', 15, 2)->nullable()->comment('الميزانية السنوية');
            $table->date('establishment_date')->comment('تاريخ الإنشاء');
            $table->text('description')->nullable()->comment('وصف القسم');
            $table->json('equipment')->nullable()->comment('قائمة المعدات الرئيسية');
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
        Schema::dropIfExists('departments');
    }
};
