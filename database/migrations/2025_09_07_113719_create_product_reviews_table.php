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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_products');
            $table->unsignedBigInteger('id_clients');
           // $table->foreign('id_products')->on('products')->references('id_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_clients')->on('clients')->references('id_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->nullable(); // de 1 a 5
            $table->text('comments')->nullable();
            $table->enum('status',['pendente','aprovado','rejeitado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
