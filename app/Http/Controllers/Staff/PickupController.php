<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PickupRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PickupController extends Controller
{
    /**
     * RQ29: Nhân viên xem danh sách yêu cầu giao nhận
     */
    public function index(Request $request)
    {
        $query = PickupRequest::with(['booking.user', 'booking.pet', 'pickupStaff'])
            ->where('pickup_staff_id', auth()->id())
            ->orWhere('status', 'pending');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_pickup_at', $request->date);
        }

        $pickups = $query->latest()->paginate(15);

        return view('staff.pickups.index', compact('pickups'));
    }

    /**
     * RQ29: Nhân viên xem chi tiết yêu cầu giao nhận
     */
    public function show(PickupRequest $pickup)
    {
        $pickup->load(['booking.user', 'booking.pet', 'booking.services', 'pickupStaff']);

        return view('staff.pickups.show', compact('pickup'));
    }

    /**
     * RQ29: Nhân viên nhận yêu cầu giao nhận
     */
    public function accept(Request $request, PickupRequest $pickup)
    {
        abort_if($pickup->status !== 'pending', 403);

        $pickup->update([
            'pickup_staff_id' => auth()->id(),
            'status' => 'assigned',
            'scheduled_pickup_at' => $request->scheduled_pickup_at ?? now()->addHours(2),
        ]);

        Notification::send(
            $pickup->booking->user_id,
            'pickup_assigned',
            'Nhân viên đã được phân công nhận thú',
            "Nhân viên {$pickup->pickupStaff->full_name} sẽ đến nhận {$pickup->booking->pet->name} vào lúc " . $pickup->scheduled_pickup_at->format('H:i d/m/Y') . ".",
            route('customer.bookings.show', $pickup->booking),
            'truck',
            'info'
        );

        return back()->with('success', 'Đã nhận yêu cầu giao nhận.');
    }

    /**
     * RQ29: Cập nhật trạng thái đã nhận thú
     */
    public function pickedUp(PickupRequest $pickup)
    {
        abort_unless($pickup->pickup_staff_id === auth()->id(), 403);
        abort_if($pickup->status !== 'assigned', 403);

        $pickup->update([
            'status' => 'picked_up',
            'actual_pickup_at' => now(),
        ]);

        Notification::send(
            $pickup->booking->user_id,
            'pet_picked_up',
            'Đã nhận thú cưng',
            "Nhân viên đã đến nhận {$pickup->booking->pet->name}. Pet đang trên đường đến cửa hàng.",
            route('customer.bookings.show', $pickup->booking),
            'truck',
            'success'
        );

        return back()->with('success', 'Đã cập nhật trạng thái đã nhận thú.');
    }

    /**
     * RQ29: Cập nhật trạng thái đã giao trả
     */
    public function delivered(PickupRequest $pickup)
    {
        abort_unless($pickup->pickup_staff_id === auth()->id(), 403);
        abort_if(!in_array($pickup->status, ['picked_up', 'assigned']), 403);

        $pickup->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        Notification::send(
            $pickup->booking->user_id,
            'pet_delivered',
            'Thú cưng đã được giao trả',
            "{$pickup->booking->pet->name} đã được giao trả thành công. Cảm ơn bạn đã sử dụng dịch vụ!",
            route('customer.bookings.show', $pickup->booking),
            'check-circle',
            'success'
        );

        return back()->with('success', 'Đã cập nhật trạng thái giao trả.');
    }

    /**
     * RQ29: Thêm ghi chú của nhân viên
     */
    public function addNote(Request $request, PickupRequest $pickup)
    {
        abort_unless($pickup->pickup_staff_id === auth()->id(), 403);

        $request->validate(['staff_notes' => 'required|string|max:500']);

        $pickup->update(['staff_notes' => $request->staff_notes]);

        return back()->with('success', 'Đã cập nhật ghi chú.');
    }
}