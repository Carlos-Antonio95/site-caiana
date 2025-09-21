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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_carts');
            $table->unsignedBigInteger('id_products');
            $table->foreign('id_carts')->references('id')->on('carts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_products')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity')->default(1);   
            $table->decimal('price', 10, 2);
            $table->string('session_id', 100)->nullable()->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
