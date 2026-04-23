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
        // Thêm các user tracking fields
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->string('avatar', 255)->nullable()->after('full_name');
            $table->date('date_of_birth')->nullable()->after('avatar');
            $table->softDeletes();
        });

        // Thêm is_active và hên index cho users
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('deleted_at');
            $table->index(['role_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_login_at', 'last_login_ip', 'avatar', 'date_of_birth', 'deleted_at']);
            // $table->dropColumn('is_active'); // nếu thêm
        });
    }
};
