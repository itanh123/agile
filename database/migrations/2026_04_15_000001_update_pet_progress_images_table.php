<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pet_progress_images', function (Blueprint $table) {
            $table->dropColumn('stage');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete()->after('booking_id');
            $table->timestamp('taken_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pet_progress_images', function (Blueprint $table) {
            $table->string('stage', 100)->nullable()->after('image_path');
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn('uploaded_by');
        });
    }
};
