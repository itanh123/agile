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
        $reviews = Review::whereHas('booking', fn ($q) => $q->where('user_id', auth()->id()))->latest()->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->get();

        return view('customer.reviews.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'nullable|string',
        ]);

        $booking = Booking::where('id', $data['booking_id'])->where('user_id', auth()->id())->where('status', 'completed')->firstOrFail();
        Review::create($data + ['booking_id' => $booking->id]);

        return redirect()->route('customer.reviews.index')->with('success', 'Review submitted.');
    }
}
