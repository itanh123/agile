@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Member Area</span>
                <h1 class="text-white fw-bold mb-0">My Reviews</h1>
            </div>
            <a href="{{ route('customer.reviews.create') }}" class="btn-premium">
                <i class="bi bi-star me-1"></i> Write a Review
            </a>
        </div>

        <div class="row g-4">
            @forelse($reviews as $review)
                <div class="col-md-6">
                    <div class="glass-card p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-muted small text-uppercase fw-bold mb-1">Booking #{{ $review->booking_id }}</div>
                                <div class="d-flex gap-1 text-warning">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="bi bi-star{{ $i < $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-white fw-bold">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <div class="text-muted small">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="glass p-3 border-0 text-white italic" style="font-style: italic;">
                            "{{ $review->comment ?? 'No comment provided.' }}"
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 glass">
                    <div class="display-3 mb-3 opacity-10">⭐</div>
                    <p class="text-muted mb-0">You haven't shared any reviews yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
