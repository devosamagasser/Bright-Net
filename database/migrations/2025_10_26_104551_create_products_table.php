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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('product_group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->foreignId('data_template_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->string('code')->index();
            $table->text('disclaimer')->nullable();
            $table->string('color')->nullable();
            $table->string('style')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('application')->nullable();
            $table->string('origin')->nullable();
            $table->unique(['family_id', 'code']);
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->char('locale', 5)->index();
            $table->unique(['product_id', 'locale']);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('products');
    }
};
