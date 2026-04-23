<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fix các vấn đề về FK và indexes bị thiếu.
     */
    public function up(): void
    {
        // 1. Đảm bảo pets.breed_id có FK
        // Kiểm tra xem FK đã tồn tại chưa
        $fkExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.table_constraints 
            WHERE constraint_schema = DATABASE() 
              AND table_name = 'pets' 
              AND constraint_name = 'pets_breed_id_foreign'
        ")[0]->count;

        if (!$fkExists) {
            Schema::table('pets', function (Blueprint $table) {
                $table->foreign('breed_id')
                    ->references('id')
                    ->on('pet_breeds')
                    ->onDelete('restrict');
            });
        }

        // 2. Đảm bảo bookings.staff_id có FK (nếu chưa có)
        $fkExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.table_constraints 
            WHERE constraint_schema = DATABASE() 
              AND table_name = 'bookings' 
              AND constraint_name = 'bookings_staff_id_foreign'
        ")[0]->count;

        if (!$fkExists) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('staff_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null');
            });
        }

        // 3. Đảm bảo bookings.promotion_id có FK (nếu chưa có)
        $fkExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.table_constraints 
            WHERE constraint_schema = DATABASE() 
              AND table_name = 'bookings' 
              AND constraint_name = 'bookings_promotion_id_foreign'
        ")[0]->count;

        if (!$fkExists) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->foreign('promotion_id')
                    ->references('id')
                    ->on('promotions')
                    ->onDelete('set null');
            });
        }

        // 4. Fix pet_categories.updated_at typo (nếu còn tồn tại)
        // Kiểm tra column có tồn tại không
        $hasColumn = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.columns 
            WHERE table_schema = DATABASE() 
              AND table_name = 'pet_categories' 
              AND column_name = 'updated_at?'
        ")[0]->count;

        if ($hasColumn) {
            Schema::table('pet_categories', function (Blueprint $table) {
                $table->renameColumn('updated_at?', 'updated_at');
            });
        }

        // 5. Thêm index cho booking_id trong booking_services nếu chưa có
        // (đã có unique, nhưng đảm bảo index riêng cho booking_id)
        $indexExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
              AND table_name = 'booking_services' 
              AND index_name = 'idx_booking_id'
        ")[0]->count;

        if (!$indexExists) {
            Schema::table('booking_services', function (Blueprint $table) {
                $table->index('booking_id', 'idx_booking_id');
            });
        }

        // 6. Thêm index cho service_id trong booking_services
        $indexExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
              AND table_name = 'booking_services' 
              AND index_name = 'idx_service_id'
        ")[0]->count;

        if (!$indexExists) {
            Schema::table('booking_services', function (Blueprint $table) {
                $table->index('service_id', 'idx_service_id');
            });
        }

        // 7. Thêm index cho breed_id trong pets
        $indexExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
              AND table_name = 'pets' 
              AND index_name = 'idx_pets_breed_id'
        ")[0]->count;

        if (!$indexExists) {
            Schema::table('pets', function (Blueprint $table) {
                $table->index('breed_id', 'idx_pets_breed_id');
            });
        }

        // 8. Thêm index cho created_at trong bookings
        $indexExists = DB::select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
              AND table_name = 'bookings' 
              AND index_name = 'idx_bookings_created_at'
        ")[0]->count;

        if (!$indexExists) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('created_at', 'idx_bookings_created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop added indexes
        Schema::table('pets', function (Blueprint $table) {
            $table->dropIndex(['idx_pets_breed_id']);
            $table->dropForeign(['breed_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['promotion_id']);
            $table->dropIndex(['idx_bookings_created_at']);
        });

        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropIndex(['idx_booking_id']);
            $table->dropIndex(['idx_service_id']);
        });

        // Không revert ENUM conversion vì không thể
    }
};
