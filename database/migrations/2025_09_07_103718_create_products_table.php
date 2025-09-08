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
            $table->id();
            $table->unsignedBigInteger('id_categories');
            $table->foreign('id_categories')->references('id')->on('categories');
            $table->string('product_name',150);
            $table->text('description')->nullable();
            $table->decimal('price',10,2);
            $table->integer('stock_quantity')->default(0);
            $table->enum('status',['ativo','inativo'])->default('ativo');
            $table->timestamps();
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
