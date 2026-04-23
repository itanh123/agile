<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('notifications');
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type', 50);
            $table->string('title', 255);
            $table->text('content');
            $table->string('link', 500)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('color', 20)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index('type');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};