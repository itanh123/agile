@extends('layouts.app')
@section('title', 'Đánh giá dịch vụ')

@section('content')
<div class="row justify-content-center animate-fade-up">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Phản hồi khách hàng</span>
                <h1 class="text-white fw-bold mb-0">Đánh Giá Dịch Vụ</h1>
            </div>
            <a href="{{ route('customer.reviews.index') }}" class="btn-outline-premium btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Lịch sử đánh giá
            </a>
        </div>

        <div class="glass-card p-4 p-md-5">
            <form method="POST" action="{{ route('customer.reviews.store') }}" id="reviewForm">
                @csrf
                
                <div class="mb-5">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-3">Chọn dịch vụ cần đánh giá</label>
                    <select name="booking_id" class="form-control-premium @error('booking_id') is-invalid @enderror" id="bookingSelect">
                        @if($selectedBooking)
                            <option value="{{ $selectedBooking->id }}" selected>
                                {{ $selectedBooking->booking_code }} - {{ $selectedBooking->appointment_at->format('d/m/Y') }} 
                                ({{ $selectedBooking->pet->name ?? 'Thú cưng' }})
                            </option>
                        @else
                            <option value="">-- Chọn một dịch vụ đã hoàn thành --</option>
                        @endif
                        
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                {{ $booking->booking_code }} - {{ $booking->appointment_at->format('d/m/Y') }} 
                                ({{ $booking->pet->name ?? 'Thú cưng' }})
                            </option>
                        @endforeach
                    </select>
                    @error('booking_id')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center mb-5">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-4 d-block">Mức độ hài lòng của bạn</label>
                    <div class="star-rating">
                        <input type="radio" name="rating" id="star-5" value="5" class="d-none">
                        <label for="star-5" title="5 stars"><i class="bi bi-star"></i></label>
                        
                        <input type="radio" name="rating" id="star-4" value="4" class="d-none">
                        <label for="star-4" title="4 stars"><i class="bi bi-star"></i></label>
                        
                        <input type="radio" name="rating" id="star-3" value="3" class="d-none">
                        <label for="star-3" title="3 stars"><i class="bi bi-star"></i></label>
                        
                        <input type="radio" name="rating" id="star-2" value="2" class="d-none">
                        <label for="star-2" title="2 stars"><i class="bi bi-star"></i></label>
                        
                        <input type="radio" name="rating" id="star-1" value="1" class="d-none">
                        <label for="star-1" title="1 star"><i class="bi bi-star"></i></label>
                    </div>
                    @error('rating')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-2">Tiêu đề đánh giá</label>
                    <input type="text" name="title" class="form-control-premium @error('title') is-invalid @enderror" 
                           placeholder="VD: Rất hài lòng, Dịch vụ tuyệt vời..." value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="form-label text-muted small text-uppercase fw-bold mb-2">Chia sẻ cảm nhận của bạn</label>
                    <textarea name="comment" class="form-control-premium @error('comment') is-invalid @enderror" 
                              rows="5" placeholder="Hãy kể về trải nghiệm của bạn và thú cưng tại đây...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-premium w-100 py-3 shadow-lg">
                    <i class="bi bi-send-fill me-2"></i> Gửi Đánh Giá Ngay
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Star Rating Style */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 15px;
}

.star-rating label {
    font-size: 2.5rem;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.star-rating label i::before {
    content: "\f588"; /* bi-star */
}

.star-rating input:checked ~ label i::before,
.star-rating label:hover ~ label i::before,
.star-rating label:hover i::before {
    content: "\f586"; /* bi-star-fill */
    color: #fbbf24;
}

.star-rating label:hover {
    transform: scale(1.2);
}

/* Form Styles */
.form-control-premium {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: white;
    padding: 12px 20px;
    transition: all 0.3s ease;
}

.form-control-premium:focus {
    background: rgba(255, 255, 255, 0.08);
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    color: white;
    outline: none;
}

.form-control-premium::placeholder {
    color: #6b7280;
}

.form-control-premium option {
    background: #1f2937;
    color: white;
}

.invalid-feedback {
    font-size: 0.85rem;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating label');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            // Optional: add some animation or sound
        });
    });
});
</script>
@endpush
@endsection
