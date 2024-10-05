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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id')->unsigned();
            $table->integer('barcod')->unsigned()->unique()->nullable();
            $table->string('product_name')->unique();
            $table->integer('Categorie_id')->unsigned()->nullable();
            $table->decimal('Product_price', 8, 2)->unsigned();
            $table->integer('quantity')->unsigned()->nullable();
            $table->decimal('Regular_discount', 8, 2)->nullable()->unsigned();
            $table->decimal('Special_discount', 8, 2)->nullable()->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('Currency_id')->unsigned()->nullable();
            $table->decimal('Total_price', 8, 2)->unsigned()->nullable();
            
            $table->timestamps();

            $table->foreign('Categorie_id')->references('categorie_id')->on('categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('Currency_id')->references('currency_id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
