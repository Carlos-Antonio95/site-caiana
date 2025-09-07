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
        Schema::create('products__variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_products');
            $table->foreign('id_products')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('size',10);
            $table->string('color',50);
            $table->decimal('additional_price',10,2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products__variants');
    }
};
