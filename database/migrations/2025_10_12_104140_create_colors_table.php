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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('hex_code');
            $table->timestamps();
        });
        Schema::create('color_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('locale')->index();
            $table->unique(['color_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('color_translations');
        Schema::dropIfExists('colors');
    }
};
