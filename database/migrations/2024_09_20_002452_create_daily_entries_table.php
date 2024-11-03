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
        Schema::create('daily_entries', function (Blueprint $table) {
            $table->increments('entrie_id')->unsigned();
            $table->decimal('Amount_debit',15,2)->nullable();
            $table->decimal('Amount_Credit',15,2)->nullable();
            $table->unsignedInteger('account_debit_id')->nullable();
            $table->unsignedInteger('account_Credit_id')->nullable();
            $table->text('Statement')->nullable();
            $table->integer('Daily_page_id')->unsigned();
            $table->string('Currency_name')->nullable();
            $table->unsignedInteger('User_id');
            $table->string('Invoice_type')->nullable();
            // $table->integer('Type_migration')->nullable();
            $table->integer('Invoice_id')->nullable()->unsigned(); 

            $table->integer('accounting_period_id')->unsigned();
            $table->enum('status_debit', ['مرحل', 'غير مرحل'])->default('غير مرحل'); //المدين        
            $table->enum('status', ['مرحل', 'غير مرحل'])->default('غير مرحل');   //الدئن      
               $table->timestamps();

            //    $table->foreign('accounting_period_id')->references('accounting_period_id')->on('accounting_periods');
               $table->foreign('account_Credit_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
               $table->foreign('account_debit_id')->references('sub_account_id')->on('sub_accounts')->onDelete('set null');
             

            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('Daily_page_id')->references('page_id')->on('general_journal');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_entries');
    }
};
