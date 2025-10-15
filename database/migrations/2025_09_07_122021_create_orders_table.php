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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('id_clients');
    $table->unsignedBigInteger('id_addresses');
    $table->enum('status', ['pendente', 'pago', 'aprovado', 'enviado', 'entregue', 'cancelado'])->default('pendente');
    $table->boolean('stock_decremented')->default(false);
    $table->decimal('total_value', 10, 2);
    $table->foreign('id_clients')->references('id')->on('clients')->onUpdate('cascade')->onDelete('cascade');
    $table->foreign('id_addresses')->references('id')->on('addresses')->onUpdate('cascade')->onDelete('cascade');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
