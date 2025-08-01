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
        Schema::table('double_entries', function (Blueprint $table) {
            $table->enum('account_type', ['دائن', 'مدين']);
            $table->dropColumn('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('double_entries', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });
    }
};
