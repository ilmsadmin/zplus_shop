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
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->unsignedInteger('bagisto_order_id')->nullable()->after('status');
            $table->foreign('bagisto_order_id')->references('id')->on('orders')->onDelete('set null');
            $table->index('bagisto_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_transactions', function (Blueprint $table) {
            $table->dropForeign(['bagisto_order_id']);
            $table->dropIndex(['bagisto_order_id']);
            $table->dropColumn('bagisto_order_id');
        });
    }
};
