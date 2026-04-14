<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('name');
            $table->string('health_status')->nullable()->after('weight');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('service_mode', ['at_store', 'at_home'])->default('at_store')->after('appointment_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('service_mode');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'health_status']);
        });
    }
};
