<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Payment;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use App\Models\Role;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    /**
     * RQ26: Xem danh sách dịch vụ (không cần đăng nhập)
     */
    public function services(Request $request)
    {
        $query = Service::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }

        $services = $query->where('is_active', true)->paginate(12)->withQueryString();

        return view('guest.services', compact('services'));
    }

    /**
     * RQ27: Xem chi tiết dịch vụ (không cần đăng nhập)
     */
    public function serviceDetail(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('guest.service-detail', compact('service'));
    }

    /**
     * RQ28: Đăng ký nhanh bằng số điện thoại
     */
    public function quickRegister(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string|min:10|max:20',
            'full_name' => 'required|string|max:255',
        ]);

        // Kiểm tra xem số điện thoại đã tồn tại chưa
        $existingUser = User::where('phone', $data['phone'])->first();

        if ($existingUser) {
            // Đăng nhập nhanh cho user đã tồn tại
            auth()->login($existingUser);
            return redirect()->route('customer.dashboard')->with('info', 'Chào mừng trở lại!');
        }

        // Tạo tài khoản mới
        $customerRole = Role::where('slug', 'customer')->orWhere('slug', 'user')->first();

        $user = User::create([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'email' => 'guest_' . Str::random(8) . '@guest.local',
            'password' => Hash::make(Str::random(12)),
            'role_id' => $customerRole?->id,
        ]);

        auth()->login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Đăng ký thành công! Vui lòng cập nhật email của bạn trong hồ sơ.');
    }

    /**
     * Đặt lịch nhanh cho khách vãng lai (RQ28)
     */
    public function quickBooking(Request $request)
    {
        $data = $request->validate([
            'service_id' => 'required|exists:services,id',
            'phone' => 'required|string|min:10|max:20',
            'pet_name' => 'required|string|max:100',
            'appointment_at' => 'required|date|after:now',
            'note' => 'nullable|string',
        ]);

        $service = Service::find($data['service_id']);

        // Kiểm tra hoặc tạo user
        $user = User::where('phone', $data['phone'])->first();
        $customerRole = Role::where('slug', 'customer')->orWhere('slug', 'user')->first();

        if (!$user) {
            $user = User::create([
                'name' => $data['pet_name'] . "'s owner",
                'full_name' => $data['pet_name'] . "'s owner",
                'phone' => $data['phone'],
                'email' => 'guest_' . Str::random(8) . '@guest.local',
                'password' => Hash::make(Str::random(12)),
                'role_id' => $customerRole?->id,
            ]);
        }

        // Tạo pet nếu chưa có
        $pet = Pet::firstOrCreate(
            ['user_id' => $user->id, 'name' => $data['pet_name']],
            ['category_id' => 1]
        );

        // Tạo booking
        $booking = Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'pet_id' => $pet->id,
            'appointment_at' => Carbon::parse($data['appointment_at']),
            'status' => 'pending',
            'service_mode' => 'at_store',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
            'subtotal' => $service->price,
            'discount_amount' => 0,
            'total_amount' => $service->price,
            'note' => $data['note'] ?? null,
        ]);

        $booking->services()->attach($service->id, [
            'quantity' => 1,
            'unit_price' => $service->price,
            'line_total' => $service->price,
        ]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => 'pending',
            'changed_by' => $user->id,
            'note' => 'Booking created by guest user',
            'changed_at' => now(),
        ]);

        // Gửi notification xác nhận
        Notification::sendBookingConfirmation($booking);

        // Tạo payment record
        Payment::create([
            'booking_id' => $booking->id,
            'transaction_code' => 'PAY-' . strtoupper(Str::random(8)),
            'payment_method' => 'cash',
            'status' => 'pending',
            'amount' => $booking->total_amount,
        ]);

        // Đăng nhập user
        auth()->login($user);

        return redirect()->route('customer.bookings.show', $booking)
            ->with('success', "Đặt lịch thành công! Mã booking: {$booking->booking_code}");
    }
}