<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pickup_requests');
        Schema::create('pickup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('pickup_code', 30)->unique();
            $table->enum('status', ['pending', 'assigned', 'picked_up', 'delivered', 'cancelled'])->default('pending');
            $table->foreignId('pickup_staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('pickup_address', 500);
            $table->string('pickup_phone', 20);
            $table->text('pickup_note')->nullable();
            $table->timestamp('scheduled_pickup_at')->nullable();
            $table->timestamp('actual_pickup_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('staff_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'scheduled_pickup_at']);
            $table->index('pickup_staff_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_requests');
    }
};