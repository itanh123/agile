<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'pet', 'staff'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('customer'), fn ($q) => $q->whereHas('user', fn ($x) => $x->where('full_name', 'like', '%' . $request->customer . '%')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $staffUsers = User::whereHas('role', fn ($q) => $q->where('slug', 'staff'))->get();
        $booking->load(['user', 'pet', 'services', 'logs', 'images', 'payment']);

        return view('admin.bookings.show', compact('booking', 'staffUsers'));
    }

    public function assignStaff(Request $request, Booking $booking)
    {
        $data = $request->validate(['staff_id' => 'required|exists:users,id']);
        $booking->update(['staff_id' => $data['staff_id'], 'status' => 'confirmed']);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => 'confirmed',
            'changed_by' => auth()->id(),
            'note' => 'Staff assigned by admin',
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Staff assigned.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
            'note' => 'nullable|string',
        ]);
        $booking->update(['status' => $data['status']]);
        if ($data['status'] === 'completed') {
            $booking->update(['payment_status' => 'paid']);
        }

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => $data['status'],
            'changed_by' => auth()->id(),
            'note' => $data['note'] ?? 'Status updated by admin',
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Booking status updated.');
    }
}
