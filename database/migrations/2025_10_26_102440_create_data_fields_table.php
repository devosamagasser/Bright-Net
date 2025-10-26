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
        Schema::create('data_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_template_id')->constrained()->onDelete('cascade');
            $table->string('type'); // text, number, select, boolean, date, multiselect
            $table->boolean('is_required')->default(false);
            $table->json('options')->nullable();
            $table->boolean('is_filterable')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('data_field_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_field_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->text('placeholder')->nullable();
            $table->char('locale', 5)->index();
            $table->unique(['data_field_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_field_translations');
        Schema::dropIfExists('data_fields');
    }
};
