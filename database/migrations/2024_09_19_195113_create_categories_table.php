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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('categorie_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('Categorie_name');
            $table->decimal('Purchase_price', 15, 2)->nullable();
            $table->decimal('Selling_price', 15, 2)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
            // $table->foreign('product_id')->references('product_id')->on('products');



            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
