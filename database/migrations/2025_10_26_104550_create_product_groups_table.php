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
        Schema::create('product_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->foreignId('data_template_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_groups');
    }
};
