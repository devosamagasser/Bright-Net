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
        Schema::create('quotation_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable');
            $table->string('activity_type'); // edit, create, delete, etc.
            $table->text('old_object')->nullable();
            $table->text('new_object')->nullable();
            $table->foreignId('quotation_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_activity_logs');
    }
};
