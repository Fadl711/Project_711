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
        Schema::create('manufacturing_costs', function (Blueprint $table) {
            $table->id();
 $table->integer('production_order_id')->comment('أمر الإنتاج المرتبط');
    $table->enum('cost_type', [
        'material', 
        'labor',
        'overhead',
        'energy',
        'depreciation', 'other']
          )->comment('نوع التكلفة');
                      $table->integer('accounting_period_id')->unsigned();

    $table->decimal('amount', 15, 2)->comment('المبلغ');
    $table->integer('gl_account_id')->nullable()->comment('حساب دفتر الأستاذ');
    $table->date('cost_date')->comment('تاريخ التكلفة');
    $table->text('description')->nullable()->comment('وصف التكلفة');
    $table->json('details')->nullable()->comment('تفاصيل إضافية');
    $table->integer('created_by')->comment('مسجل التكلفة');
    $table->timestamps();
    $table->index(['production_order_id', 'cost_type'])->comment('فهرس لتحليل التكاليف'); 
    
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_costs');
    }
};
