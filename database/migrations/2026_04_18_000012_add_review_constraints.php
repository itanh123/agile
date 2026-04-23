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
        // Thêm unique constraint cho reviews.booking_id (1 booking - 1 review)
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique('booking_id', 'uniq_booking_review');
        });

        // Thêm indexes
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['rating', 'is_public']);
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('uniq_booking_review');
            $table->dropIndex(['rating', 'is_public']);
            $table->dropIndex(['is_public']);
        });
    }
};
