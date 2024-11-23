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
        Schema::disableForeignKeyConstraints();

        Schema::create('sales_invoices', function (Blueprint $table) {
   // عمود رقم الفاتورة
   $table->increments('sales_invoice_id')->unsigned()->comment('رقم الفاتورة');

   // عمود ID العميل
   $table->integer('Customer_id')->unsigned()->nullable()->comment('معرف العميل');
   // عمود نوع الدفع

   $table->enum('payment_type', ['cash', 'on_credit', 'transfer'])->comment('نوع الدفع: نقداً، على الحساب، تحويل');
   // عمود حالة الدفع
   $table->enum('payment_status', ['paid', 'unpaid', 'partial'])->default('unpaid')->comment('حالة الدفع: مدفوع، غير مدفوع، جزئي');

   // عمود إجمالي التكلفة
   $table->decimal('total_price', 15, 2)->comment('إجمالي تكلفة الفاتورة');

   // عمود إجمالي سعر البيع
   $table->decimal('total_price_sale', 15, 2)->comment('إجمالي سعر البيع');
     // إضافة عمود "الخصم" (اختياري حسب احتياجك)
     $table->decimal('discount', 15, 2)->default(0)->comment('قيمة الخصم الممنوح');

     // إضافة عمود "الإجمالي الصافي بعد الخصم"
     $table->decimal('net_total_after_discount', 15, 2)->default(0)->comment('الإجمالي الصافي بعد الخصم'); 

   // عمود ID المستخدم
   $table->integer('User_id')->unsigned()->comment('معرف المستخدم الذي قام بإنشاء الفاتورة');

   // عمود المبلغ المدفوع
   $table->decimal('paid_amount', 15, 2)->default(0)->comment('المبلغ المدفوع');

   // عمود المبلغ المتبقي
   $table->decimal('remaining_amount', 15, 2)->default(0)->comment('المبلغ المتبقي');


   // عمود ID العملة
   $table->integer('currency_id')->unsigned()->comment('معرف العملة');

   // عمود سعر الصرف
   $table->decimal('exchange_rate', 15, 2)->nullable()->comment('سعر الصرف للعملة');

   // عمود نوع العملية
   $table->string('transaction_type')->default('مبيعات')->comment('نوع العملية');


   // عمود الدافع للشحن
   $table->enum('shipping_bearer', ['customer', 'merchant'])->default('customer')->comment('الطرف المسؤول عن الشحن: العميل أو التاجر');
   $table->decimal('shipping_amount', 15, 2)->nullable()->comment('مبلغ الشحن  ');

   // عمود ID الفترة المحاسبية
   $table->integer('accounting_period_id')->unsigned()->comment('معرف الفترة المحاسبية');
   // الطوابع الزمنية لإنشاء وتحديث السجلات
   $table->timestamps();
   
   // إنشاء علاقات المفاتيح الأجنبية
   $table->foreign('Customer_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null')->comment('الربط مع جدول الحسابات الفرعية');
   $table->foreign('User_id')->references('id')->on('users')->comment('الربط مع جدول المستخدمين');
});
Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
