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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orders');
            $table->foreign('id_orders')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('method', ['cartao_credito', 'cartao_debito', 'dinheiro', 'pix']);
            $table->decimal('amount', 10, 2);
            $table->enum('status',['pendente','aprovado', 'recusado'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
