@extends('layouts.app')
@section('title', 'Lịch sử đánh giá')

@section('content')
<div class="animate-fade-up">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <span class="badge-premium mb-2 d-inline-block">Khu vực thành viên</span>
            <h1 class="text-white fw-bold mb-0">Lịch Sử Đánh Giá</h1>
        </div>
        <a href="{{ route('customer.reviews.create') }}" class="btn-premium">
            <i class="bi bi-star-fill me-1"></i> Viết đánh giá mới
        </a>
    </div>

    <div class="row g-4">
        @forelse($reviews as $review)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex gap-1 text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="text-muted small">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>

                    <h5 class="text-white fw-bold mb-2">{{ $review->title }}</h5>
                    <p class="text-muted small mb-4 flex-grow-1">"{{ $review->comment }}"</p>

                    <div class="mt-auto pt-3 border-top border-white border-opacity-10">
                        <div class="d-flex align-items-center gap-2 mb-2">
                             <div class="p-1 bg-light bg-opacity-10 rounded-circle text-center" style="width: 25px; height: 25px; line-height: 15px; font-size: 0.8rem;">
                                🐾
                            </div>
                            <span class="text-white small fw-medium">{{ $review->booking->pet->name ?? 'Unknown' }}</span>
                        </div>
                        <div class="text-primary small fw-bold">
                            {{ $review->booking->booking_code }}
                        </div>
                        <div class="text-muted small mt-1">
                            @foreach($review->booking->services as $service)
                                {{ $service->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="glass-card p-5 text-center">
                    <div class="fs-1 text-muted opacity-25 mb-3">
                        <i class="bi bi-chat-heart"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Chưa có đánh giá nào</h4>
                    <p class="text-muted mb-4">Bạn chưa thực hiện đánh giá cho các dịch vụ đã sử dụng.</p>
                    <a href="{{ route('customer.reviews.create') }}" class="btn-premium">Đánh giá ngay</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $reviews->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
