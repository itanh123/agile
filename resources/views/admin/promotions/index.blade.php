@extends('layouts.admin')
@section('title', 'Quản lý Voucher')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Vouchers</span>
@endpush

@section('content')
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h2 class="mb-1">Quản lý Voucher</h2>
        <p class="text-muted mb-0">Tạo và quản lý các mã giảm giá cho khách hàng.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('admin.promotions.index') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control-admin" placeholder="Tìm kiếm mã hoặc tiêu đề..." value="{{ request('search') }}">
                <button class="btn-admin btn-admin-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
        <a href="{{ route('admin.promotions.create') }}" class="btn-admin btn-admin-primary">
            <i class="bi bi-plus-lg"></i> Thêm Voucher
        </a>
    </div>
</div>

<!-- Thống kê -->
<div class="row g-3 mb-4 mt-1">
    <div class="col-md-3">
        <div class="admin-card text-center border-start border-4 border-primary">
            <h3 class="mb-1">{{ $promotions->total() }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Tổng số Voucher</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center border-start border-4 border-success">
            <h3 class="mb-1" style="color: var(--admin-success);">{{ $activeCount ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Đang hoạt động</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center border-start border-4 border-danger">
            <h3 class="mb-1" style="color: var(--admin-danger);">{{ $expiredPromotions ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Đã hết hạn</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center border-start border-4 border-info">
            <h3 class="mb-1" style="color: var(--admin-info);">{{ number_format($totalSavings ?? 0, 0) }}đ</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Tổng tiền đã giảm</p>
        </div>
    </div>
</div>

<!-- Danh sách Voucher -->
@if($promotions->isEmpty())
    <div class="admin-card">
        <div class="empty-state">
            <i class="bi bi-tag text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Không tìm thấy voucher nào</h4>
            <p>Hãy tạo mã giảm giá đầu tiên để thu hút khách hàng của bạn.</p>
            <a href="{{ route('admin.promotions.create') }}" class="btn-admin btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Thêm Voucher
            </a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($promotions as $promotion)
            <div class="col-md-6 col-xl-4">
                <div class="admin-card h-100 position-relative transition-all hover-shadow">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge-code" style="background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; padding: 0.4rem 0.8rem; border-radius: 0.5rem; font-size: 0.9rem; font-weight: 700; letter-spacing: 1px;">
                            {{ $promotion->code }}
                        </span>
                        <span class="status-badge {{ $promotion->is_active ? 'active' : 'inactive' }}">
                            <i class="bi bi-{{ $promotion->is_active ? 'check-circle' : 'x-circle' }}"></i>
                            {{ $promotion->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                        </span>
                    </div>
                    
                    <h5 class="mb-2">{{ $promotion->title }}</h5>
                    <p class="text-muted mb-3" style="font-size: 0.85rem; height: 38px; overflow: hidden;">{{ Str::limit($promotion->description, 80) }}</p>
                    
                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="p-2 rounded bg-light flex-fill">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase;">Giảm giá</span>
                            <span class="fs-4 fw-bold text-primary">
                                @if($promotion->discount_type === 'percent')
                                    {{ (float)$promotion->discount_value }}%
                                @else
                                    {{ number_format($promotion->discount_value, 0) }}đ
                                @endif
                            </span>
                        </div>
                        <div class="p-2 rounded bg-light flex-fill">
                            <span class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase;">Sử dụng</span>
                            <span class="fs-4 fw-bold text-dark">
                                {{ $promotion->used_count ?? 0 }}<span class="text-muted fs-6" style="font-weight: 400;">/{{ $promotion->usage_limit ?: '∞' }}</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item mt-4 border-top pt-3">
                        <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                            <span class="text-muted"><i class="bi bi-calendar-event me-2"></i>Thời hạn:</span>
                            <span class="fw-medium text-dark">
                                {{ $promotion->start_at ? \Carbon\Carbon::parse($promotion->start_at)->format('d/m/y') : 'N/A' }} 
                                - 
                                {{ $promotion->end_at ? \Carbon\Carbon::parse($promotion->end_at)->format('d/m/y') : 'Vô thời hạn' }}
                            </span>
                        </div>
                        
                        @if($promotion->min_order_amount > 0)
                            <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                                <span class="text-muted"><i class="bi bi-cart me-2"></i>Đơn tối thiểu:</span>
                                <span class="fw-medium text-dark">{{ number_format($promotion->min_order_amount, 0) }}đ</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-flex gap-2 mt-4 pt-2">
                        <a href="{{ route('admin.promotions.show', $promotion) }}" class="btn-admin btn-admin-secondary btn-admin-sm flex-fill">
                            <i class="bi bi-eye"></i> Chi tiết
                        </a>
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-admin btn-admin-primary btn-admin-sm">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $promotions->withQueryString()->links() }}
    </div>
@endif
@endsection