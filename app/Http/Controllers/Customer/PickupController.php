<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PickupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PickupController extends Controller
{
    /**
     * RQ29: Khách hàng xem danh sách yêu cầu giao nhận
     */
    public function index()
    {
        $pickups = PickupRequest::with(['booking.services', 'pickupStaff'])
            ->whereHas('booking', fn($q) => $q->where('user_id', auth()->id()))
            ->latest()
            ->paginate(10);

        return view('customer.pickups.index', compact('pickups'));
    }

    /**
     * RQ29: Khách hàng xem chi tiết yêu cầu giao nhận
     */
    public function show(PickupRequest $pickup)
    {
        abort_unless($pickup->booking->user_id === auth()->id(), 403);
        $pickup->load(['booking.services', 'pickupStaff']);

        return view('customer.pickups.show', compact('pickup'));
    }

    /**
     * RQ29: Khách hàng tạo yêu cầu giao nhận cho một booking
     */
    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($booking->service_mode === 'pickup', 403);

        $data = $request->validate([
            'pickup_address' => 'required|string|max:500',
            'pickup_phone' => 'required|string|min:10|max:20',
            'scheduled_pickup_at' => 'required|date|after:now',
            'pickup_note' => 'nullable|string|max:500',
        ]);

        $existingPickup = PickupRequest::where('booking_id', $booking->id)
            ->whereNotIn('status', ['cancelled', 'delivered'])
            ->first();

        if ($existingPickup) {
            return back()->with('error', 'Yêu cầu giao nhận đã tồn tại cho booking này.');
        }

        $pickup = PickupRequest::create([
            'booking_id' => $booking->id,
            'pickup_code' => 'PK-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'pickup_address' => $data['pickup_address'],
            'pickup_phone' => $data['pickup_phone'],
            'pickup_note' => $data['pickup_note'] ?? null,
            'scheduled_pickup_at' => $data['scheduled_pickup_at'],
        ]);

        return redirect()->route('customer.pickups.show', $pickup)
            ->with('success', 'Yêu cầu giao nhận đã được tạo. Vui lòng đợi nhân viên xác nhận.');
    }

    /**
     * RQ29: Khách hàng hủy yêu cầu giao nhận
     */
    public function cancel(PickupRequest $pickup)
    {
        abort_unless($pickup->booking->user_id === auth()->id(), 403);
        abort_if($pickup->status === 'picked_up', 403);
        abort_if($pickup->status === 'delivered', 403);

        $pickup->update(['status' => 'cancelled']);

        return back()->with('success', 'Yêu cầu giao nhận đã được hủy.');
    }
}