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
        Schema::create('promotion_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_promotions');
            $table->unsignedBigInteger('id_products');
            $table->foreign('id_promotions')->on('promotions')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_products')->on('products')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('percentage_discount',5,2)->nullable();
            $table->decimal('promotional_price',5,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
    }
};
