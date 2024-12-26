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
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->increments('permission_id');
            $table->boolean('Readability')->default(true);
            $table->boolean('Writing_ability')->default(false);
            $table->boolean('Deletion_authority')->default(false);
            $table->boolean('Ability_modify')->default(false);
            $table->integer('User_id')->unsigned();
            $table->timestamps();

            $table->foreign('User_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
