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
        // Thêm soft deletes cho bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Thêm các cột mới cho bookings (backward compatible)
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_type', 20)->default('standard')->after('service_mode');
            $table->text('cancelled_reason')->nullable()->after('note');
            $table->foreignId('cancelled_by')->nullable()->after('cancelled_reason')
                ->constrained('users')->nullOnDelete();
        });

        // Thêm indexes cho bookings (quan trọng nhất)
        Schema::table('bookings', function (Blueprint $table) {
            // Query by user + status rất thường xuyên
            $table->index(['user_id', 'status']);
            // Query staff schedule
            $table->index(['staff_id', 'appointment_at', 'status']);
            // Reporting: bookings theo status và thời gian tạo
            $table->index(['status', 'created_at']);
            // Query upcoming bookings
            $table->index(['status', 'appointment_at']);
            // Search by booking_code (đã có unique, nhưng thêm index cho LIKE search)
            $table->index('booking_code');
        });

        // Fix enum service_mode: thêm 'pickup' value
        // MySQL không cho phép alter enum trực tiếp, cần tạo mới và chuyển dữ liệu
        // Tạm thời giữ nguyên, sẽ xử lý trong migration sau (convert ENUM -> VARCHAR)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['deleted_at', 'booking_type', 'cancelled_reason', 'cancelled_by']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['staff_id', 'appointment_at', 'status']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['status', 'appointment_at']);
            $table->dropIndex(['booking_code']);
        });
    }
};
