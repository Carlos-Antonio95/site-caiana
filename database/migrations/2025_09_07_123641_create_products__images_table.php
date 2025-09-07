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
        Schema::create('products__images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_products');
            $table->foreign('id_products')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image_path',255);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products__images');
    }
};
