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
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_admins');
            $table->foreign('id_admins')->references('id')->on('Users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('activity', 200);
            $table->string('ip_address', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activities');
    }
};
