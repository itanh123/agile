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
        // Indexes cho pet_medical_history
        Schema::table('pet_medical_history', function (Blueprint $table) {
            $table->index(['pet_id', 'visit_date']);
            $table->index('visit_date');
        });

        // Indexes cho pet_progress_images
        Schema::table('pet_progress_images', function (Blueprint $table) {
            $table->index(['booking_id', 'created_at']);
            $table->index('uploaded_by');
        });

        // Indexes cho messages
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['user_id', 'is_read', 'sent_at']);
            $table->index('sent_at');
        });

        // Indexes cho booking_status_logs
        Schema::table('booking_status_logs', function (Blueprint $table) {
            $table->index(['booking_id', 'created_at']);
            $table->index('changed_by');
        });

        // Indexes cho role_permissions (composite PK đã có, thêm index cho permission_id)
        Schema::table('role_permissions', function (Blueprint $table) {
            // PK là (role_id, permission_id) - đã có
            // Thêm index cho permission_id để query nhanh (đã có qua unique, nhưng có thể cần thêm)
            $table->index('permission_id');
        });

        // Indexes cho user_permissions
        Schema::table('user_permissions', function (Blueprint $table) {
            // PK là (user_id, permission_id) - đã có
            $table->index('permission_id');
        });

        // Indexes cho promotions
        Schema::table('promotions', function (Blueprint $table) {
            $table->index(['is_active', 'start_at', 'end_at']);
            $table->index(['code', 'is_active']);
            $table->index('start_at');
        });

        // Indexes cho pet_breeds
        Schema::table('pet_breeds', function (Blueprint $table) {
            $table->index('is_active');
        });

        // Indexes cho pet_categories
        Schema::table('pet_categories', function (Blueprint $table) {
            $table->index('is_active');
        });

        // Indexes cho services (đã thêm ở migration 005)
        Schema::table('services', function (Blueprint $table) {
            // Đã thêm trong migration 005
        });

        // Indexes cho bookings (đã thêm ở migration 004)
        Schema::table('bookings', function (Blueprint $table) {
            // Đã thêm trong migration 004
        });

        // Indexes cho payments (đã thêm ở migration 007)
        Schema::table('payments', function (Blueprint $table) {
            // Đã thêm trong migration 007
        });

        // Indexes cho user_notifications (đã thêm ở migration 008)
        Schema::table('user_notifications', function (Blueprint $table) {
            // Đã thêm trong migration 008
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // pet_medical_history indexes
        Schema::table('pet_medical_history', function (Blueprint $table) {
            $table->dropIndex(['pet_id', 'visit_date']);
            $table->dropIndex(['visit_date']);
        });

        // pet_progress_images indexes
        Schema::table('pet_progress_images', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'created_at']);
            $table->dropIndex(['uploaded_by']);
        });

        // messages indexes
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_read', 'sent_at']);
            $table->dropIndex(['sent_at']);
        });

        // booking_status_logs indexes
        Schema::table('booking_status_logs', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'created_at']);
            $table->dropIndex(['changed_by']);
        });

        // role_permissions index
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropIndex(['permission_id']);
        });

        // user_permissions index
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->dropIndex(['permission_id']);
        });

        // promotions indexes
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'start_at', 'end_at']);
            $table->dropIndex(['code', 'is_active']);
            $table->dropIndex(['start_at']);
        });

        // pet_breeds index
        Schema::table('pet_breeds', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        // pet_categories index
        Schema::table('pet_categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};
