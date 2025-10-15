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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_clients');
            $table->foreign('id_clients')->references('id')->on ('clients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('road',200);//rua
            $table->string('number',10);
            $table->string('complement',50)->nullable()->default('Não informado');
            $table->string('referenc',200)->nullable()->default('Não informado');
            $table->string('neighborhood',100);//bairro kkk
            $table->string('city',100);
            $table->string('state',100);
            $table->string('cep',20);
            $table->string('country');//pais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
