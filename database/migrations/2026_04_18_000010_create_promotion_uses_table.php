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
        Schema::create('promotion_uses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('discount_amount', 10, 2)->comment('Số tiền giảm giá thực tế');
            $table->timestamp('used_at')->useCurrent();
            $table->text('note')->nullable();

            $table->unique(['booking_id', 'promotion_id'], 'uniq_booking_promotion');
            $table->index(['promotion_id', 'user_id']);
            $table->index('used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_uses');
    }
};
