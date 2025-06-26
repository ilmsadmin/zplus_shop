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
        Schema::create('warranty_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "1 tháng", "3 tháng", etc.
            $table->string('slug')->unique(); // "1-month", "3-months", etc.
            $table->integer('duration_months'); // 1, 3, 6, 12
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('price', 15, 2)->default(0); // Optional price for warranty
            $table->timestamps();
            
            $table->index('is_active');
            $table->index('duration_months');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_packages');
    }
};