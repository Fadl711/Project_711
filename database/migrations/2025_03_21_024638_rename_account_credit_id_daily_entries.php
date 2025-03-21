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
            $table->renameColumn('account_Credit_id', 'account_credit_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_entries', function (Blueprint $table) {
            $table->renameColumn('account_credit_id', 'account_Credit_id');
        });
    }
};
