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
            $table->integer('Barcode')->unsigned()->unique()->nullable();
            $table->string('product_name')->unique();
            $table->integer('Quantity')->nullable();
            $table->double('Purchase_price')->nullable()->unsigned();;
           $table->double('Selling_price')->nullable()->unsigned();;
            $table->double('Total')->nullable();
            $table->double('Cost')->nullable()->unsigned();;
            $table->double('Regular_discount')->nullable()->unsigned();
            $table->double('Special_discount')->nullable()->unsigned();
            $table->double('Profit')->nullable();
            $table->string('note')->nullable();


            $table->integer('warehouse_id')->unsigned()->nullable();
            $table->integer('currency_id')->unsigned()->nullable();
             $table->integer('User_id')->unsigned();
            $table->integer('Categorie_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('Categorie_id')->references('categorie_id')->on('categories')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses');
            $table->foreign('User_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('currency_id')->on('currencies');
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
