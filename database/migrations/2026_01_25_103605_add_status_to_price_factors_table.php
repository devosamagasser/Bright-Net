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
        Schema::table('price_factors', function (Blueprint $table) {
            $table->string('status')->default('active')->after('factor');
            $table->foreignId('parent_factor_id')->nullable()->after('status')->constrained('price_factors')->nullOnDelete();
            $table->text('notes')->nullable()->after('parent_factor_id');
            
            $table->index(['supplier_id', 'status']);
        });
        
        Schema::table('product_price_factors', function (Blueprint $table) {
            $table->index(['price_id', 'factor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_price_factors', function (Blueprint $table) {
            $table->dropIndex(['price_id', 'factor_id']);
        });
        
        Schema::table('price_factors', function (Blueprint $table) {
            $table->dropIndex(['supplier_id', 'status']);
            $table->dropForeign(['parent_factor_id']);
            $table->dropColumn(['status', 'parent_factor_id', 'notes']);
        });
    }
};
