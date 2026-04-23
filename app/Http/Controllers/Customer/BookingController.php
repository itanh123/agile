<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\Payment;
use App\Models\Pet;
use App\Models\Promotion;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['pet', 'services', 'staff'])->where('user_id', auth()->id())->latest()->paginate(10);

        return view('customer.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $pets = Pet::where('user_id', auth()->id())->get();
        $services = Service::where('is_active', true)->get();

        return view('customer.bookings.create', compact('pets', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'appointment_at' => 'required|date|after:now',
            'service_mode' => 'required|in:at_store,at_home',
            'payment_method' => 'required|in:cash,vnpay',
            'promotion_code' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        abort_unless(Pet::where('id', $data['pet_id'])->where('user_id', auth()->id())->exists(), 403);
        $services = Service::whereIn('id', $data['service_ids'])->get();
        $subtotal = (float) $services->sum('price');
        $discount = 0;
        $promotionId = null;

        if (! empty($data['promotion_code'])) {
            $promotion = Promotion::where('code', $data['promotion_code'])
                ->where('is_active', true)
                ->where('start_at', '<=', now())
                ->where('end_at', '>=', now())
                ->first();
            if ($promotion) {
                $promotionId = $promotion->id;
                $discount = $promotion->discount_type === 'percent'
                    ? ($subtotal * $promotion->discount_value / 100)
                    : min($subtotal, $promotion->discount_value);
            }
        }

        $booking = Booking::create([
            'booking_code' => 'BK-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
            'pet_id' => $data['pet_id'],
            'promotion_id' => $promotionId,
            'appointment_at' => Carbon::parse($data['appointment_at']),
            'status' => 'pending',
            'service_mode' => $data['service_mode'],
            'payment_status' => 'unpaid',
            'payment_method' => $data['payment_method'],
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => max($subtotal - $discount, 0),
            'note' => $data['note'] ?? null,
        ]);

        foreach ($services as $service) {
            $booking->services()->attach($service->id, [
                'quantity' => 1,
                'unit_price' => $service->price,
                'line_total' => $service->price,
            ]);
        }

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => 'pending',
            'changed_by' => auth()->id(),
            'note' => 'Booking created by customer',
            'changed_at' => now(),
        ]);

        // RQ07: Gửi xác nhận đặt lịch cho khách hàng
        $booking->notifyConfirmation();

        // Nếu chọn VNPay, chuyển hướng sang xử lý thanh toán luôn
        if ($booking->payment_method === 'vnpay') {
            return redirect()->route('customer.payments.process', ['booking' => $booking, 'method' => 'vnpay']);
        }

        // Nếu chọn tiền mặt
        Payment::create([
            'booking_id' => $booking->id,
            'transaction_code' => 'PAY-' . strtoupper(Str::random(8)),
            'payment_method' => $booking->payment_method,
            'status' => 'pending',
            'amount' => $booking->total_amount,
        ]);

        return redirect()->route('customer.bookings.show', $booking)->with('success', 'Bạn đã đặt lịch thành công.');
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $booking->load(['pet', 'services', 'images', 'logs', 'payment', 'review']);

        return view('customer.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_if(in_array($booking->status, ['completed', 'cancelled'], true), 422);
        $booking->update(['status' => 'cancelled']);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => 'cancelled',
            'changed_by' => auth()->id(),
            'note' => 'Cancelled by customer',
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Booking cancelled.');
    }

    public function reschedule(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $data = $request->validate(['appointment_at' => 'required|date|after:now']);
        $booking->update(['appointment_at' => $data['appointment_at'], 'status' => 'pending']);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => 'pending',
            'changed_by' => auth()->id(),
            'note' => 'Rescheduled by customer',
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Booking rescheduled.');
    }
}
