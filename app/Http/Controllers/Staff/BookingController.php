<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\PetProgressImage;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'pet'])
            ->where('staff_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('staff.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);
        $booking->load(['user', 'pet', 'services', 'images', 'logs']);

        return view('staff.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);
        $data = $request->validate([
            'status' => 'required|in:confirmed,processing,completed,cancelled',
            'note' => 'nullable|string',
        ]);

        $booking->update(['status' => $data['status']]);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => $data['status'],
            'changed_by' => auth()->id(),
            'note' => $data['note'] ?? 'Updated by staff',
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function uploadImage(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);
        $data = $request->validate([
            'image_path' => 'required|string|max:255',
            'stage' => 'nullable|string|max:100',
            'caption' => 'nullable|string',
        ]);

        PetProgressImage::create($data + ['booking_id' => $booking->id, 'taken_at' => now()]);

        return back()->with('success', 'Progress image added.');
    }

    public function addNote(Request $request, Booking $booking)
    {
        abort_unless($booking->staff_id === auth()->id(), 403);
        $data = $request->validate(['note' => 'required|string']);

        BookingStatusLog::create([
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'changed_by' => auth()->id(),
            'note' => $data['note'],
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Note added.');
    }
}
