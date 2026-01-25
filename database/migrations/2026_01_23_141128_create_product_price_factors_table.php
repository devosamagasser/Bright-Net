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
        Schema::create('product_price_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->constrained('product_prices')->cascadeOnDelete();
            $table->foreignId('factor_id')->constrained('price_factors')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_factors');
    }
};
