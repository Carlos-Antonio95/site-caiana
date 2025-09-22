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
        Schema::table('orders', function (Blueprint $table) {
            // Adiciona a coluna para controlar se o estoque já foi decrementado
            $table->boolean('stock_decremented')->default(false)->after('status');

            // Altera o enum status para incluir 'aprovado'
            $table->enum('status', ['pendente', 'pago', 'aprovado', 'enviado', 'entregue', 'cancelado'])
                  ->default('pendente')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Remove a coluna stock_decremented
            $table->dropColumn('stock_decremented');

            // Volta o enum status para o original
            $table->enum('status', ['pendente', 'pago', 'enviado', 'entregue', 'cancelado'])
                  ->default('pendente')
                  ->change();
        });
    }
};
