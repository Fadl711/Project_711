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
        Schema::create('general_ledge_page', function (Blueprint $table) {
            $table->increments('general_ledge_page_id');
            $table->decimal('Amount_debtor',8,2);
            $table->string('Statement_debtor');
            $table->integer('Daily_entry_debtor_id')->unsigned();
            $table->date('date_debtor');
            $table->decimal('Amount_creditor',8,2);
            $table->string('Statement_creditor');
            $table->integer('Daily_entry_creditor_id')->unsigned();
            $table->date('date_creditor');
            $table->integer('Daily_Page_id')->unsigned();
            $table->integer('General_ledger_page_number_id')->unsigned();
            $table->timestamps();

            $table->foreign('Daily_entry_debtor_id')->references('entrie_id')->on('daily_entries');
            $table->foreign('Daily_entry_creditor_id')->references('entrie_id')->on('daily_entries');
            $table->foreign('Daily_Page_id')->references('page_id')->on('general_journal');
            $table->foreign('General_ledger_page_number_id')->references('general_ledge_id')->on('general_ledge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_ledge_page');
    }
};
