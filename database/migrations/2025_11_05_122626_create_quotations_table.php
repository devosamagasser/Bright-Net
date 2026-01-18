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
        Schema::create('quotations', function (Blueprint $table): void {
            $table->id();
            $table->string('status')->default('draft');
            $table->string('reference')->nullable()->index();
            $table->string('title')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->char('currency', 3)->default('EGP');
            $table->json('meta')->nullable();
            $table->json('general_notes')->nullable();
            $table->json('warranty')->nullable();
            $table->json('warranty_and_payments')->nullable();
            $table->boolean('discount_applied')->default(true);
            $table->boolean('vat_applied')->default(true);
            $table->unsignedBigInteger('log_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
