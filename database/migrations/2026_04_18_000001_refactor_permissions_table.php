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
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Thêm các columns mới vào permissions (nullable để backward compatible)
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('resource_type', 50)->nullable()->after('module');
            $table->string('action', 50)->default('view')->after('resource_type');
            $table->foreignId('group_id')->nullable()->after('action')
                ->constrained('permission_groups')->nullOnDelete();
            $table->string('description')->nullable()->change(); // Ensure text -> nullable
        });

        // Indexes cho permissions
        Schema::table('permissions', function (Blueprint $table) {
            $table->index(['resource_type', 'action']);
            $table->index('group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropColumn(['resource_type', 'action', 'group_id']);
        });

        Schema::dropIfExists('permission_groups');
    }
};
