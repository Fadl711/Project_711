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
        Schema::create('general_entries', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('sub_id')->unsigned();
            $table->integer('Main_id')->unsigned();
           $table->integer('Daily_entry_id')->unsigned();
           $table->integer('Daily_Page_id')->unsigned();
           $table->integer('User_id')->unsigned();
           $table->integer('General_ledger_page_number_id')->unsigned();
           $table->integer('accounting_period_id')->unsigned();
           $table->integer('typeAccount');
           $table->enum('entry_type', ['debit', 'credit']); // نوع القيد: مدين أو دائن
           $table->decimal('amount', 15, 2); // المبلغ
           $table->string('Currency_name')->nullable();
           $table->string('Invoice_type')->nullable();
           $table->integer('Invoice_id')->nullable();


           // العملة
           $table->text('description'); // الوصف أو البيان
           $table->date('entry_date'); // تاريخ القيد
           $table->enum('status', ['مرحل', 'غير مرحل']);         
           $table->timestamps();
           $table->foreign('sub_id')->references('sub_account_id')->on('sub_accounts');
           $table->foreign('Main_id')->references('main_account_id')->on('main_accounts');
           $table->foreign('User_id')->references('id')->on('users');

           $table->foreign('accounting_period_id')->references('accounting_period_id')->on('accounting_periods');
           $table->foreign('Daily_entry_id')->references('entrie_id')->on('daily_entries');
           $table->foreign('Daily_Page_id')->references('page_id')->on('general_journals');
          $table->foreign('General_ledger_page_number_id')->references('general_ledge_id')->on('general_ledges');
      
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_entries');
    }
};
