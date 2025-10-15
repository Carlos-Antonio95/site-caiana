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
        Schema::create('order_items', function (Blueprint $table) {
    $table->id(); 
    $table->unsignedBigInteger('id_order'); 
    $table->unsignedBigInteger('id_variants')->nullable(); 
    $table->unsignedBigInteger('id_product');
    $table->string('title', 150);
    $table->decimal('price', 10, 2); 
    $table->integer('quantity');
    $table->timestamps();

    $table->foreign('id_order')->references('id')->on('orders')
          ->onUpdate('cascade')->onDelete('cascade');

    $table->foreign('id_variants')->references('id')->on('product_variants')
          ->onUpdate('cascade')->onDelete('cascade');

    $table->foreign('id_product')->references('id')->on('products')
          ->onUpdate('cascade')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
