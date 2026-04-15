<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'pet', 'staff'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('customer'), fn ($q) => $q->whereHas('user', fn ($x) => $x->where('full_name', 'like', '%' . $request->customer . '%')))
            ->when($request->filled('code'), fn ($q) => $q->where('booking_code', 'like', '%' . $request->code . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = Booking::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $totalRevenue = Booking::where('status', 'completed')->sum('total_amount');

        return view('admin.bookings.index', compact('bookings', 'statusCounts', 'totalRevenue'));
    }

    public function show(Booking $booking)
    {
        $staffUsers = User::whereHas('role', fn ($q) => $q->where('slug', 'staff'))->get();
        $booking->load(['user', 'pet', 'services']);

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
