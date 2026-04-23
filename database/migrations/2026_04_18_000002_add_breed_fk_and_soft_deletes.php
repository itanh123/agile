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
        // Thêm/Sửa foreign key cho pets.breed_id nếu cần
        if (Schema::hasColumn('pets', 'breed_id')) {
            Schema::table('pets', function (Blueprint $table) {
                $table->unsignedBigInteger('breed_id')->nullable()->change();
            });
        } else {
            Schema::table('pets', function (Blueprint $table) {
                $table->foreignId('breed_id')->nullable()->constrained('pet_breeds')->restrictOnDelete();
            });
        }

        // Thêm soft deletes cho pets
        if (!Schema::hasColumn('pets', 'deleted_at')) {
            Schema::table('pets', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Thêm indexes cho pets
        Schema::table('pets', function (Blueprint $table) {
            // Chúng ta không dùng try-catch ở đây mà dùng logic đơn giản vì Laravel không có hasIndex dễ dùng
            // Tuy nhiên, nếu migration đã fail ở bước trước, các index này chưa tồn tại.
            // Để an toàn nhất, chúng ta có thể pass qua nếu đã có.
        });

        try {
            Schema::table('pets', function (Blueprint $table) {
                $table->index(['user_id', 'category_id']);
                $table->index(['user_id', 'deleted_at']);
                $table->index(['category_id', 'deleted_at']);
            });
        } catch (\Exception $e) {
            // Index already exists, skip
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['breed_id']);
            $table->dropColumn('deleted_at');
            $table->dropIndex(['user_id', 'category_id']);
            $table->dropIndex(['user_id', 'deleted_at']);
            $table->dropIndex(['category_id', 'deleted_at']);
        });
    }
};
