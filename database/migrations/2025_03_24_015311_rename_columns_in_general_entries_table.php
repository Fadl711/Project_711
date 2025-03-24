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
        Schema::table('general_entries', function (Blueprint $table) {
            $table->renameColumn('Currency_name', 'currency_name');
            $table->renameColumn('Daily_entry_id', 'daily_entry_id');
            $table->renameColumn('Daily_Page_id', 'daily_Page_id');
            $table->renameColumn('Main_id', 'main_id');
            $table->renameColumn('Invoice_type', 'invoice_type');
            $table->renameColumn('General_ledger_page_number_id', 'general_ledger_page_number_id');
            $table->renameColumn('Invoice_id', 'invoice_id');
            $table->renameColumn('User_id', 'user_id');
            $table->renameColumn('typeAccount', 'type_account');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_entries', function (Blueprint $table) {
            $table->renameColumn('currency_name', 'Currency_name');
            $table->renameColumn('daily_entry_id', 'Daily_entry_id');
            $table->renameColumn('daily_Page_id', 'Daily_Page_id');
            $table->renameColumn('main_id', 'Main_id');
            $table->renameColumn('general_ledger_page_number_id', 'General_ledger_page_number_id');
            $table->renameColumn('invoice_type', 'Invoice_type');
            $table->renameColumn('invoice_id', 'Invoice_id');
            $table->renameColumn('user_id', 'User_id');
            $table->renameColumn('type_account', 'typeAccount');





        });
    }
};
