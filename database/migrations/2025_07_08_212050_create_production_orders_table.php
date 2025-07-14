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
        // 3. جدول أوامر الإنتاج
        Schema::create('production_orders', function (Blueprint $table) {
    $table->id();
    $table->string('order_number')->unique()->comment('رقم أمر الإنتاج الفريد');
    $table->integer('product_id')->comment('المنتج المطلوب تصنيعه');
    $table->integer('line_id')->comment('خط الإنتاج');
    $table->decimal('planned_quantity', 12, 3)->comment('الكمية المخططة');
    $table->decimal('produced_quantity', 12, 3)->default(0)->comment('الكمية المنتجة');
    $table->decimal('approved_quantity', 12, 3)->default(0)->comment('الكمية المعتمدة');
    $table->date('start_date')->comment('التاريخ المخطط للبدء');
    $table->date('end_date')->comment('التاريخ المخطط للانتهاء');
    $table->timestamp('actual_start')->nullable()->comment('البدء الفعلي');
    $table->timestamp('actual_end')->nullable()->comment('الانتهاء الفعلي');
    $table->enum('status', ['draft', 'planned', 'in_progress', 'paused', 'completed', 'canceled'])->default('draft')->comment('حالة أمر الإنتاج');
    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->comment('أولوية التنفيذ');
    $table->decimal('estimated_cost', 15, 2)->comment('التكلفة التقديرية');
    $table->decimal('actual_cost', 15, 2)->nullable()->comment('التكلفة الفعلية');
    $table->integer('sales_order_id')->nullable()->comment('أمر البيع المرتبط');
    $table->integer('created_by')->comment('منشئ الأمر');
    $table->integer('approved_by')->nullable()->comment('المعتمد');
    $table->text('notes')->nullable()->comment('ملاحظات عامة');
    $table->text('cancellation_reason')->nullable()->comment('سبب الإلغاء');
    
    $table->timestamps();
    $table->softDeletes();
    $table->index(['status', 'start_date'])->comment('فهرس لتسريع استعلامات الجدولة');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
