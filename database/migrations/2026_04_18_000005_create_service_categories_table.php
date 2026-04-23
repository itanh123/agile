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
        // Tạo bảng service_categories
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->text('description')->nullable();
            $table->string('icon', 50)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Thêm service_category_id vào services
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('service_category_id')->nullable()
                ->constrained('service_categories')->nullOnDelete()->after('service_type');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->integer('sort_order')->default(0)->after('is_featured');
            $table->boolean('requires_staff')->default(false)->after('sort_order');
        });

        // Thêm indexes cho services
        Schema::table('services', function (Blueprint $table) {
            $table->index(['service_type', 'is_active']);
            $table->index(['service_category_id', 'is_active']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropColumn(['service_category_id', 'is_featured', 'sort_order', 'requires_staff']);
            $table->dropIndex(['service_type', 'is_active']);
            $table->dropIndex(['service_category_id', 'is_active']);
            $table->dropIndex(['is_active']);
        });

        Schema::dropIfExists('service_categories');
    }
};
