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
        Schema::create('type_accounts', function (Blueprint $table) {
            $table->increments('type_account_id');
            $table->string('account_name');
            $table->string('Nature_account');
            $table->integer('migration_ID')->unsigned();
            $table->timestamps();

            $table->foreign('migration_ID')->references('migration_ID')->on('deportation_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_accounts');
    }
};
