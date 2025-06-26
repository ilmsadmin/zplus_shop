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
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->string('warranty_number')->unique(); // Auto-generated warranty number
            $table->unsignedBigInteger('warranty_package_id');
            $table->foreign('warranty_package_id')->references('id')->on('warranty_packages')->cascadeOnDelete();
            
            // Product information
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('product_serial')->index(); // Serial number of the product
            $table->string('product_name'); // Store product name at time of warranty
            $table->string('product_sku'); // Store product SKU at time of warranty
            
            // Order information (from ViPOS)
            $table->string('order_number')->nullable(); // Order number from POS
            $table->unsignedBigInteger('pos_transaction_id')->nullable();
            $table->foreign('pos_transaction_id')->references('id')->on('pos_transactions')->nullOnDelete();
            
            // Customer information
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->string('customer_name'); // Store customer name at time of warranty
            $table->string('customer_phone'); // Store customer phone at time of warranty
            $table->string('customer_email')->nullable(); // Store customer email at time of warranty
            
            // Warranty dates
            $table->date('start_date'); // Warranty start date
            $table->date('end_date'); // Warranty end date
            $table->date('purchase_date'); // Purchase date
            
            // Status and notes
            $table->enum('status', ['active', 'expired', 'claimed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->text('claim_history')->nullable(); // JSON field for claim history
            
            // Created by
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('admins');
            
            $table->timestamps();
            
            // Indexes for fast searching
            $table->index(['product_serial', 'status']);
            $table->index(['customer_phone', 'status']);
            $table->index(['warranty_number']);
            $table->index(['status', 'end_date']);
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};