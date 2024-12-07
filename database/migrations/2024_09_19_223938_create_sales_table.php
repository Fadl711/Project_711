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

        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sale_id')->unsigned();
            $table->string('Product_name');
            $table->integer('product_id')->unsigned();
            $table->string('Category_name')->nullable();
            $table->integer('accounting_period_id')->unsigned();
            $table->string('Barcode')->nullable(); // إضافة العمود Barcode
            $table->decimal('quantity', 10, 2); // حيث 10 هو العدد الإجمالي للأرقام و 2 هو عدد الأرقام بعد الفاصلة العشرية
            $table->decimal('Quantityprice', 9, 2)->comment('كمية المنتج  المباعه حسب الوحدة');
            $table->decimal('Selling_price', 15, 2);  // سعر الوحدة للمنتج
            $table->string('note')->nullable();
            $table->decimal('total_amount', 15, 2); // إجمالي المبلغ
            $table->decimal('discount_rate', 5, 2)->nullable()->default(0); // نسبة الخصم (٪)
            $table->decimal('tax_rate', 5, 2)->nullable()->default(0); // نسبة الضريبة (٪)
            $table->decimal('discount', 15, 2)->nullable()->default(0); // خصم المطبقة (إن وجد)
            $table->decimal('tax', 15, 2)->nullable()->default(0); // ضريبة المطبقة (إن وجدت)
            $table->decimal('total_price', 15, 2);// إجمالي السعر بعد الخصم والضريبة
            $table->string('currency')->nullable(); // العملة المستخدمة في الفاتورة
            $table->decimal('shipping_cost', 10, 2)->nullable()->default(0); // تكلفة الشحن (إن وجدت)
            $table->integer('financial_account_id')->unsigned()->nullable()->comment('المخزني)');
            $table->integer('warehouse_to_id')->unsigned()->nullable()->comment('المخزن الوجهة (للتحويل المخزني)');
            $table->integer('Customer_id')->unsigned()->nullable();// الرقم التعريفي للحساب المالي
            $table->integer('User_id')->unsigned();
            $table->integer('Invoice_id')->unsigned();
            $table->integer('supplier_id')->unsigned()->nullable();

            $table->unsignedTinyInteger('transaction_type')->comment('يحدد نوع المعاملة');

            $table->timestamps();
            $table->foreign('warehouse_to_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('supplier_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('financial_account_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('Customer_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Invoice_id')->references('sales_invoice_id')->on('sales_invoices');
        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
