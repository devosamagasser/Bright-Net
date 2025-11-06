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
        Schema::create('quotation_products', function (Blueprint $table): void {
            $table->id();
            $table->string('item_ref')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('family_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_code');
            $table->string('product_name');
            $table->text('product_description')->nullable();
            $table->json('product_snapshot');
            $table->json('roots_snapshot');
            $table->json('price_snapshot')->nullable();
            $table->text('notes')->nullable();
            $table->string('delivery_time_unit')->nullable();
            $table->string('delivery_time_value')->nullable();
            $table->boolean('vat_included')->default(false);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('list_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount', 8, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->char('currency', 3)->default('EGP');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_products');
    }
};
