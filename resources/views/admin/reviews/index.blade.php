@extends('layouts.admin')
@section('title', 'Quản lý Đánh giá')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Đánh giá</span>
@endpush

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="admin-card stats-card bookings shadow-sm">
            <div class="stats-icon"><i class="bi bi-star-fill"></i></div>
            <h5 class="admin-card-title">Trung bình Đánh giá</h5>
            <div class="d-flex align-items-center gap-2">
                <h2 class="admin-card-value">{{ number_format($avgRating, 1) }}</h2>
                <div class="text-warning fs-4 mb-2">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}"></i>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card stats-card users shadow-sm">
            <div class="stats-icon"><i class="bi bi-chat-left-text"></i></div>
            <h5 class="admin-card-title">Tổng số Đánh giá</h5>
            <h2 class="admin-card-value">{{ $totalReviews }}</h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card stats-card revenue shadow-sm">
            <div class="stats-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <h5 class="admin-card-title">Mới trong tuần</h5>
            <h2 class="admin-card-value">+{{ $recentReviews }}</h2>
        </div>
    </div>
</div>

<div class="admin-card mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold"><i class="bi bi-chat-quote me-2 text-primary"></i>Danh sách Đánh giá</h4>
        
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="d-flex gap-2">
            <select name="rating" class="form-select-admin" style="min-width: 150px;" onchange="this.form.submit()">
                <option value="">-- Lọc theo sao --</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} Sao</option>
                @endfor
            </select>
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control-admin" placeholder="Tìm kiếm nội dung..." value="{{ request('search') }}">
            </div>
            <button class="btn-admin btn-admin-primary">Tìm</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead>
                <tr>
                    <th class="ps-4">Khách hàng</th>
                    <th>Dịch vụ & Thú cưng</th>
                    <th>Đánh giá</th>
                    <th style="width: 30%;">Nội dung</th>
                    <th>Trạng thái</th>
                    <th class="text-end pe-4">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr class="animate-fade-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="table-avatar">
                                    {{ strtoupper(substr($review->booking->user->fullname ?? $review->booking->user->username ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $review->booking->user->fullname ?? $review->booking->user->username }}</div>
                                    <div class="text-muted x-small">ID: #{{ $review->booking->user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-primary">{{ $review->booking->booking_code }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-heart-pulse me-1"></i>{{ $review->booking->pet->name ?? '-' }}
                            </div>
                            <div class="text-muted x-small">
                                @foreach($review->booking->services as $srv)
                                    {{ $srv->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="text-warning small mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="badge bg-light text-dark fw-bold" style="font-size: 0.7rem;">{{ $review->rating }}/5 Sao</span>
                        </td>
                        <td>
                            <div class="fw-bold small">{{ $review->title }}</div>
                            <div class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4;">
                                "{{ $review->comment }}"
                            </div>
                        </td>
                        <td>
                            <form action="{{ route('admin.reviews.toggle-visibility', $review) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="status-badge {{ $review->is_public ? 'active' : 'inactive' }} border-0 cursor-pointer" style="cursor: pointer;">
                                    <i class="bi bi-{{ $review->is_public ? 'eye-fill' : 'eye-slash' }} me-1"></i>
                                    {{ $review->is_public ? 'Hiển thị' : 'Ẩn' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end gap-2 px-4">
                                <a href="{{ route('admin.bookings.show', $review->booking_id) }}" class="btn-admin btn-admin-secondary btn-admin-sm" title="Xem đơn hàng">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                                <button type="button" class="btn-admin btn-admin-danger btn-admin-sm" 
                                        onclick="if(confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) document.getElementById('delete-review-{{ $review->id }}').submit();">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="delete-review-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-none">
                                    @csrf @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-chat-square-dots"></i>
                                <h4>Chưa có đánh giá nào</h4>
                                <p>Khi khách hàng để lại phản hồi, các đánh giá sẽ xuất hiện ở đây.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
.x-small { font-size: 0.75rem; }
.cursor-pointer { cursor: pointer; }
.animate-fade-in { opacity: 0; animation: fadeIn 0.4s ease forwards; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
