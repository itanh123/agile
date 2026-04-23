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
        // Thêm unique constraint cho pickup_requests.booking_id (1 booking - 1 pickup request)
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->unique('booking_id', 'uniq_booking_pickup');
        });

        // Thêm indexes tối ưu
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->index(['status', 'pickup_staff_id']);
            $table->index(['scheduled_pickup_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropUnique('uniq_booking_pickup');
            $table->dropIndex(['status', 'pickup_staff_id']);
            $table->dropIndex(['scheduled_pickup_at', 'status']);
        });
    }
};
