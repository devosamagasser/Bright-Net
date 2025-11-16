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
        Schema::create('depended_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_field_id')->constrained()->onDelete('cascade');
            $table->foreignId('depends_on_field_id')->constrained('data_fields')->onDelete('cascade');
            $table->json('values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depended_fields');
    }
};
