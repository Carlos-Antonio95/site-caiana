<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_activities')) {
            Schema::create('admin_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_admins');
                $table->string('activity', 200);
                $table->string('ip_address', 50)->nullable();
                $table->timestamps();

                $table->foreign('id_admins')
                      ->references('id')
                      ->on('users') // ✅ nome minúsculo
                      ->onUpdate('cascade')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activities');
    }
};
