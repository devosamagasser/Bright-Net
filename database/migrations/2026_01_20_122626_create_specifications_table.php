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
        Schema::create('specifications', function (Blueprint $table): void {
            $table->id();
            $table->string('status')->default('draft');
            $table->string('reference')->nullable()->index();
            $table->string('title')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->json('general_notes')->nullable();
            $table->boolean('show_quantity')->default(true);
            $table->boolean('show_approval')->default(true);
            $table->boolean('show_reference')->default(true);
            $table->unsignedBigInteger('log_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
