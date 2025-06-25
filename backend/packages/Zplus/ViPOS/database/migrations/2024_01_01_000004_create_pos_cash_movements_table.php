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
    {        Schema::create('pos_cash_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_session_id');
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->cascadeOnDelete();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('admins');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['cash_in', 'cash_out', 'sale', 'refund', 'correction']);
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('pos_transaction_id')->nullable();
            $table->foreign('pos_transaction_id')->references('id')->on('pos_transactions')->nullOnDelete();
            $table->timestamp('movement_at');
            $table->timestamps();
            
            $table->index(['pos_session_id', 'type']);
            $table->index('movement_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_cash_movements');
    }
};
