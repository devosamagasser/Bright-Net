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
        Schema::create('families', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->foreignId('data_template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->change();
            $table->foreignId('supplier_department_id')
                ->constrained()
                ->onDelete('cascade');
            $table->unsignedInteger('order')
                ->default(0)
                ->index();
            $table->timestamps();
        });

        Schema::create('family_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->char('locale', 5)->index();
            $table->unique(['family_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_translations');
        Schema::dropIfExists('families');
    }
};
