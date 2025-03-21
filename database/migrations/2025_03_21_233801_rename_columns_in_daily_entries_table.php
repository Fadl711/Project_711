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
       
        Schema::table('daily_entries', function (Blueprint $table) {
            // تغيير أسماء الأعمدة من أحرف كبيرة إلى أحرف صغيرة
            $table->renameColumn('Amount_debit', 'amount_debit');
            $table->renameColumn('Amount_Credit', 'amount_credit');
            $table->renameColumn('Daily_page_id', 'daily_page_id');
            $table->renameColumn('Currency_name', 'currency_name');
            $table->renameColumn('User_id', 'user_id');
            $table->renameColumn('Invoice_type', 'invoice_type');
            $table->renameColumn('Invoice_id', 'invoice_id');
            $table->renameColumn('Statement', 'statement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->renameColumn('amount_debit', 'Amount_debit');
            $table->renameColumn('amount_credit', 'Amount_Credit');
            $table->renameColumn('daily_page_id', 'Daily_page_id');
            $table->renameColumn('currency_name', 'Currency_name');
            $table->renameColumn('user_id', 'User_id');
            $table->renameColumn('invoice_type', 'Invoice_type');
            $table->renameColumn('invoice_id', 'Invoice_id');    
            $table->renameColumn('Statement', 'statement');

            });
    }
};
