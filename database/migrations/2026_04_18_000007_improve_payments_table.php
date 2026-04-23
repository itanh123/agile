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
        Schema::table('payments', function (Blueprint $table) {
            // Thêm gateway fields
            $table->string('gateway', 50)->nullable()->after('payment_method');
            $table->string('gateway_transaction_id', 100)->nullable()->after('gateway');
            $table->json('gateway_response')->nullable()->after('gateway_transaction_id');
            $table->string('failure_reason', 255)->nullable()->after('note');
        });

        // Thêm indexes cho payments
        Schema::table('payments', function (Blueprint $table) {
            $table->index(['booking_id', 'status']);
            $table->index(['status', 'paid_at']);
            $table->index('gateway');
            $table->index('gateway_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'status']);
            $table->dropIndex(['status', 'paid_at']);
            $table->dropIndex(['gateway']);
            $table->dropIndex(['gateway_transaction_id']);
            $table->dropColumn(['gateway', 'gateway_transaction_id', 'gateway_response', 'failure_reason']);
        });
    }
};
