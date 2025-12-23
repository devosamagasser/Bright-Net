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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock');
            $table->string('code')->index();
            $table->text('disclaimer')->nullable();
            $table->unique(['family_id', 'code']);
        });
    }

};
