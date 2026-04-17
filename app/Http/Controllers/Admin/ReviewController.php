<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['booking.user', 'booking.services', 'booking.pet']);

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('booking.user', function($userQuery) use ($search) {
                      $userQuery->where('fullname', 'like', "%{$search}%")
                                ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->latest()->paginate(15);
        
        $avgRating = Review::avg('rating') ?: 0;
        $totalReviews = Review::count();
        $recentReviews = Review::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.reviews.index', compact('reviews', 'avgRating', 'totalReviews', 'recentReviews'));
    }

    public function toggleVisibility(Review $review)
    {
        $review->update([
            'is_public' => !$review->is_public
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái hiển thị của đánh giá.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá thành công.');
    }
}
