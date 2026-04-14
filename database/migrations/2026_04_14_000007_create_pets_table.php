<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('pet_categories')->cascadeOnDelete();
            $table->foreignId('breed_id')->constrained('pet_breeds')->cascadeOnDelete();
            $table->string('name', 100);
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->date('date_of_birth')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('color', 100)->nullable();
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
