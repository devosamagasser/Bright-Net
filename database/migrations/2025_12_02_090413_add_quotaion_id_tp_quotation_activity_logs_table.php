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
        Schema::table('quotation_activity_logs', function (Blueprint $table) {
            $table->foreignId('quotation_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
