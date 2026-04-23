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
        // Rename notifications -> user_notifications
        Schema::rename('notifications', 'user_notifications');

        // Thêm các columns mới
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->tinyInteger('priority')->default(2)->after('is_read'); // 1=high, 2=medium, 3=low
            $table->timestamp('expires_at')->nullable()->after('scheduled_at');
            $table->string('category', 50)->nullable()->after('type');
        });

        // Cập nhật cấu trúc index và khóa ngoại
        Schema::table('user_notifications', function (Blueprint $table) {
            // MySQL không cho xóa index nếu nó đang được dùng bởi Foreign Key
            // Do đó cần xóa FK trước, sau đó xóa index cũ, rồi mới thêm lại
            $table->dropForeign('notifications_user_id_foreign');
            $table->dropIndex('notifications_user_id_is_read_index');
            
            // Thêm các composite indexes mới tối ưu hơn
            $table->index(['user_id', 'is_read', 'created_at']);
            $table->index(['category', 'is_read']);
            $table->index(['priority', 'created_at']);
            $table->index('expires_at');

            // Thêm lại Foreign Key (lúc này nó sẽ tự động dùng index mới)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_read', 'created_at']);
            $table->dropIndex(['category', 'is_read']);
            $table->dropIndex(['priority', 'created_at']);
            $table->dropIndex(['expires_at']);
            $table->dropColumn(['priority', 'expires_at', 'category']);
        });

        Schema::rename('user_notifications', 'notifications');
    }
};
