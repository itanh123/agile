<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::whereHas('booking', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['booking.services', 'booking.pet'])
            ->latest()
            ->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function create(Request $request)
    {
        $selectedBooking = null;
        if ($request->has('booking_id')) {
            $selectedBooking = Booking::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->whereDoesntHave('review')
                ->where('id', $request->booking_id)
                ->first();
        }

        $bookings = Booking::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->when($selectedBooking, function($query) use ($selectedBooking) {
                return $query->where('id', '!=', $selectedBooking->id);
            })
            ->get();

        return view('customer.reviews.create', compact('bookings', 'selectedBooking'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:150',
            'comment' => 'required|string',
        ], [
            'booking_id.required' => 'Vui lòng chọn đơn hàng cần đánh giá.',
            'rating.required' => 'Vui lòng chọn mức độ hài lòng (số sao).',
            'title.required' => 'Vui lòng nhập tiêu đề đánh giá.',
            'comment.required' => 'Vui lòng để lại cảm nhận của bạn.',
        ]);

        $booking = Booking::where('id', $data['booking_id'])
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->firstOrFail();

        Review::create($data + ['booking_id' => $booking->id]);

        return redirect()->route('customer.reviews.index')->with('success', 'Cảm ơn bạn đã đóng góp ý kiến đánh giá!');
    }
}
