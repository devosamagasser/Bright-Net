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
        Schema::create('currency_transform_factors', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('from');
            $table->string('to');
            $table->decimal('factor', 8, 2)->default(0.000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_transform_factors');
    }
};
