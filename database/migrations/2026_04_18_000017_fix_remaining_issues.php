<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix các vấn đề nhỏ còn lại:
     * - pet_categories.updated_at? typo
     * - booking_status_logs indexes
     * - pet_breeds indexes cho is_active
     */
    public function up(): void
    {
        // 1. Fix pet_categories.updated_at? column name và thêm softDeletes
        Schema::table('pet_categories', function (Blueprint $table) {
            // Kiểm tra xem có column tên 'updated_at?' không
            $hasColumn = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.columns 
                WHERE table_schema = DATABASE() 
                AND table_name = 'pet_categories' 
                AND column_name = 'updated_at?'
            ")[0]->count;

            if ($hasColumn > 0) {
                $table->renameColumn('updated_at?', 'updated_at');
            }

            if (!Schema::hasColumn('pet_categories', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 1.1 Thêm softDeletes cho pet_breeds nếu thiếu
        if (!Schema::hasColumn('pet_breeds', 'deleted_at')) {
            Schema::table('pet_breeds', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // 2. Thêm indexes và softDeletes cho booking_status_logs
        Schema::table('booking_status_logs', function (Blueprint $table) {
            // Index cho ['booking_id', 'created_at'] và 'changed_by' đã có trong migration 013
            // Chỉ thêm index mới:
            $table->index(['status', 'created_at']);
        });

        // 3. Thêm softDeletes cho các bảng thiếu
        $tablesWithSoftDeletes = ['services', 'bookings', 'reviews', 'payments'];
        foreach ($tablesWithSoftDeletes as $tableName) {
            if (!Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }

        // 4. Thêm index cho pet_breeds.is_active (đã có trong migration 013)
        // Bỏ qua vì đã có.

        // 4. Thêm index cho bookings.booking_code (đã có unique, nhưng thêm index cho LIKE search)
        // unique index đã hỗ trợ prefix, nhưng có thể thêm thêm nếu cần
        Schema::table('bookings', function (Blueprint $table) {
            // Đã có unique, không cần thêm
        });

        // 5. Thêm index cho users.email (đã có unique)
        Schema::table('users', function (Blueprint $table) {
            // unique index đã có
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_status_logs', function (Blueprint $table) {
            $table->dropIndex(['booking_id', 'created_at']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['changed_by']);
        });

        Schema::table('pet_breeds', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        // Không revert rename column vì phức tạp
    }
};
