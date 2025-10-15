<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Illuminate\Support\enum_value;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code',50)->unique();
            $table->enum('discount_type',['valor','percentual']);
            $table->decimal('discount_value',10,2);
            $table->decimal('min_discount',10,2)->default(0);
            $table->date('expiration_date');
            $table->integer('max_use')->default(1);
            $table->boolean('active')->default(true);


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
