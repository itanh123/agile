<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Chuyển đổi tất cả ENUM columns sang VARCHAR với CHECK constraints
     * để dễ mở rộng và tránh ALTER TABLE lock.
     */
    public function up(): void
    {
        // 1. bookings.service_mode: enum('at_store','at_home') -> VARCHAR(20)
        // Thêm 'pickup' value mới
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('service_mode_new', 20)->default('at_store')->after('service_mode');
        });

        // Migrate data
        DB::statement("UPDATE bookings SET service_mode_new = service_mode WHERE service_mode IS NOT NULL");

        // Drop old column và rename new
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('service_mode');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('service_mode_new', 'service_mode');
        });

        // Thêm CHECK constraint (MySQL 8.0+)
        DB::statement("ALTER TABLE bookings ADD CONSTRAINT chk_bookings_service_mode 
                       CHECK (service_mode IN ('at_store', 'at_home', 'pickup'))");

        // 2. bookings.status: enum -> VARCHAR(20) (ít thay đổi, nhưng để đồng nhất)
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('status_new', 20)->default('pending')->after('service_mode');
        });

        DB::statement("UPDATE bookings SET status_new = status WHERE status IS NOT NULL");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        DB::statement("ALTER TABLE bookings ADD CONSTRAINT chk_bookings_status 
                       CHECK (status IN ('pending', 'confirmed', 'processing', 'completed', 'cancelled'))");

        // 3. bookings.payment_status: enum -> VARCHAR(20)
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_status_new', 20)->default('unpaid')->after('total_amount');
        });

        DB::statement("UPDATE bookings SET payment_status_new = payment_status WHERE payment_status IS NOT NULL");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('payment_status_new', 'payment_status');
        });

        DB::statement("ALTER TABLE bookings ADD CONSTRAINT chk_bookings_payment_status 
                       CHECK (payment_status IN ('unpaid', 'paid', 'refunded', 'failed'))");

        // 4. bookings.payment_method: enum -> VARCHAR(20)
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method_new', 20)->default('cash')->after('payment_status');
        });

        DB::statement("UPDATE bookings SET payment_method_new = payment_method WHERE payment_method IS NOT NULL");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('payment_method_new', 'payment_method');
        });

        DB::statement("ALTER TABLE bookings ADD CONSTRAINT chk_bookings_payment_method 
                       CHECK (payment_method IN ('cash', 'vnpay', 'momo', 'transfer'))");

        // 5. services.service_type: enum -> VARCHAR(50)
        Schema::table('services', function (Blueprint $table) {
            $table->string('service_type_new', 50)->default('grooming')->after('name');
        });

        DB::statement("UPDATE services SET service_type_new = service_type WHERE service_type IS NOT NULL");

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('service_type_new', 'service_type');
        });

        DB::statement("ALTER TABLE services ADD CONSTRAINT chk_services_service_type 
                       CHECK (service_type IN ('grooming', 'vaccination', 'spa', 'checkup', 'surgery', 'other'))");

        // 6. pets.gender: enum -> VARCHAR(10) (ít thay đổi, nhưng để đồng nhất)
        Schema::table('pets', function (Blueprint $table) {
            $table->string('gender_new', 10)->default('unknown')->after('name');
        });

        DB::statement("UPDATE pets SET gender_new = gender WHERE gender IS NOT NULL");

        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('gender');
        });

        Schema::table('pets', function (Blueprint $table) {
            $table->renameColumn('gender_new', 'gender');
        });

        DB::statement("ALTER TABLE pets ADD CONSTRAINT chk_pets_gender 
                       CHECK (gender IN ('male', 'female', 'unknown'))");

        // 7. payments.payment_method: enum -> VARCHAR(20)
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_method_new', 20)->default('cash')->after('booking_id');
        });

        DB::statement("UPDATE payments SET payment_method_new = payment_method WHERE payment_method IS NOT NULL");

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('payment_method_new', 'payment_method');
        });

        DB::statement("ALTER TABLE payments ADD CONSTRAINT chk_payments_payment_method 
                       CHECK (payment_method IN ('cash', 'vnpay', 'momo', 'transfer'))");

        // 8. payments.status: enum -> VARCHAR(20)
        Schema::table('payments', function (Blueprint $table) {
            $table->string('status_new', 20)->default('pending')->after('payment_method');
        });

        DB::statement("UPDATE payments SET status_new = status WHERE status IS NOT NULL");

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        DB::statement("ALTER TABLE payments ADD CONSTRAINT chk_payments_status 
                       CHECK (status IN ('pending', 'paid', 'failed', 'refunded'))");

        // 9. pickup_requests.status: enum -> VARCHAR(20)
        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->string('status_new', 20)->default('pending')->after('pickup_code');
        });

        DB::statement("UPDATE pickup_requests SET status_new = status WHERE status IS NOT NULL");

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('pickup_requests', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        DB::statement("ALTER TABLE pickup_requests ADD CONSTRAINT chk_pickup_requests_status 
                       CHECK (status IN ('pending', 'assigned', 'picked_up', 'delivered', 'cancelled'))");

        // 10. messages.sender: enum -> VARCHAR(10)
        Schema::table('messages', function (Blueprint $table) {
            $table->string('sender_new', 10)->after('user_id');
        });

        DB::statement("UPDATE messages SET sender_new = sender WHERE sender IS NOT NULL");

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('sender');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->renameColumn('sender_new', 'sender');
        });

        DB::statement("ALTER TABLE messages ADD CONSTRAINT chk_messages_sender 
                       CHECK (sender IN ('user', 'staff', 'ai'))");

        // 11. promotions.discount_type: enum -> VARCHAR(10)
        Schema::table('promotions', function (Blueprint $table) {
            $table->string('discount_type_new', 10)->default('percent')->after('description');
        });

        DB::statement("UPDATE promotions SET discount_type_new = discount_type WHERE discount_type IS NOT NULL");

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('discount_type');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->renameColumn('discount_type_new', 'discount_type');
        });

        DB::statement("ALTER TABLE promotions ADD CONSTRAINT chk_promotions_discount_type 
                       CHECK (discount_type IN ('percent', 'fixed'))");
    }

    /**
     * Reverse the migrations.
     *
     * Lưu ý: Không thể khôi phục ENUM từ VARCHAR một cách dễ dàng.
     * Cần tạo lại bảng với ENUM và migrate data ngược lại.
     */
    public function down(): void
    {
        // Không hỗ trợ down cho migration này vì phức tạp
        // Trong môi trường production, cần backup trước khi chạy
        throw new \Exception('Cannot reverse ENUM to VARCHAR conversion. Please restore from backup.');
    }
};
