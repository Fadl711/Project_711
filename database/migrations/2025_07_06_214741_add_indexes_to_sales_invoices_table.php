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
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->index('sales_invoice_id');
            $table->index('created_at');
            $table->index('accounting_period_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            $table->dropIndex(['sales_invoice_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['accounting_period_id']);
        });
    }
};
