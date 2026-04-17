@extends('layouts.admin')
@section('title', 'Chi tiết Voucher')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.promotions.index') }}">Vouchers</a>
<i class="bi bi-chevron-right"></i>
<span>{{ $promotion->code }}</span>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <!-- Promotion Info Card -->
        <div class="admin-card mb-4 border-0 shadow-sm overflow-hidden">
            <div style="background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 2rem; color: white; margin: -1.5rem -1.5rem 1.5rem -1.5rem; position: relative;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="badge" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 1.2rem; font-weight: 800; letter-spacing: 2px;">
                        {{ $promotion->code }}
                    </span>
                    <span class="status-badge {{ $promotion->is_active ? 'active' : 'inactive' }}" style="background: {{ $promotion->is_active ? '#10b981' : '#ef4444' }}; color: white; border: none;">
                        <i class="bi bi-{{ $promotion->is_active ? 'check-circle' : 'x-circle' }}"></i>
                        {{ $promotion->is_active ? 'Đang chạy' : 'Tạm dừng' }}
                    </span>
                </div>
                <h3 class="mb-1 fw-bold">{{ $promotion->title }}</h3>
                <p class="mb-0 text-white-50" style="font-size: 0.9rem;">ID: #VOU-{{ str_pad($promotion->id, 5, '0', STR_PAD_LEFT) }}</p>
                
                <i class="bi bi-tag-fill" style="position: absolute; bottom: -20px; right: 10px; font-size: 120px; opacity: 0.1; transform: rotate(-15deg);"></i>
            </div>
            
            <p class="text-muted mb-4" style="line-height: 1.6; font-size: 0.95rem;">{{ $promotion->description ?: 'Không có mô tả chi tiết cho voucher này.' }}</p>
            
            <div class="detail-section">
                <div class="detail-item py-2">
                    <span class="detail-label text-muted"><i class="bi bi-percent me-2"></i>Mức giảm giá</span>
                    <span class="detail-value fs-4 fw-bold text-primary">
                        @if($promotion->discount_type === 'percent')
                            {{ $promotion->discount_value }}%
                        @else
                            {{ number_format($promotion->discount_value, 0) }}đ
                        @endif
                    </span>
                </div>
                <div class="detail-item py-2">
                    <span class="detail-label text-muted"><i class="bi bi-cart-check me-2"></i>Đơn tối thiểu</span>
                    <span class="detail-value fw-bold">{{ number_format($promotion->min_order_amount, 0) }}đ</span>
                </div>
                <div class="detail-item py-2 border-top mt-2">
                    <span class="detail-label text-muted"><i class="bi bi-calendar3 me-2"></i>Ngày áp dụng</span>
                    <span class="detail-value text-dark fw-medium">{{ $promotion->start_at ? \Carbon\Carbon::parse($promotion->start_at)->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="detail-item py-2">
                    <span class="detail-label text-muted"><i class="bi bi-calendar3-x me-2"></i>Ngày kết thúc</span>
                    <span class="detail-value text-dark fw-medium">{{ $promotion->end_at ? \Carbon\Carbon::parse($promotion->end_at)->format('d/m/Y H:i') : 'Vô thời hạn' }}</span>
                </div>
                <div class="detail-item py-2">
                    <span class="detail-label text-muted"><i class="bi bi-clock-history me-2"></i>Ngày tạo</span>
                    <span class="detail-value text-dark">{{ $promotion->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-admin btn-admin-primary flex-fill py-2">
                    <i class="bi bi-pencil"></i> Chỉnh sửa Voucher
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <!-- Usage Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="admin-card text-center border-bottom border-3 border-primary">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-ticket-perforated fs-4 text-primary"></i>
                    </div>
                    <h3 class="mb-1">{{ $usageCount ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Lượt sử dụng</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center border-bottom border-3 border-info">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-people fs-4 text-info"></i>
                    </div>
                    <h3 class="mb-1">{{ $uniqueUsers ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Khách hàng</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center border-bottom border-3 border-success">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 50px; height: 50px;">
                        <i class="bi bi-currency-dollar fs-4 text-success"></i>
                    </div>
                    <h3 class="mb-1" style="color: var(--admin-success);">{{ number_format($totalSaved ?? 0, 0) }}đ</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Đã giảm tổng cộng</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Usage -->
        <div class="admin-card p-0 overflow-hidden">
            <div class="admin-card-header bg-light border-bottom p-3">
                <h5 class="admin-card-title mb-0"><i class="bi bi-clock-history me-2"></i>Lịch sử sử dụng gần đây</h5>
            </div>
            @if($promotion->bookings->isNotEmpty())
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 text-uppercase text-muted" style="font-size: 0.7rem;">Mã Đơn</th>
                                <th class="text-uppercase text-muted" style="font-size: 0.7rem;">Khách hàng</th>
                                <th class="text-uppercase text-muted" style="font-size: 0.7rem;">Tiền giảm</th>
                                <th class="text-uppercase text-muted" style="font-size: 0.7rem;">Trạng thái</th>
                                <th class="pe-3 text-uppercase text-muted" style="font-size: 0.7rem;">Ngày</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotion->bookings->sortByDesc('created_at')->take(10) as $booking)
                                <tr>
                                    <td class="ps-3"><strong>{{ $booking->booking_code }}</strong></td>
                                    <td>{{ $booking->user->fullname ?? $booking->user->username }}</td>
                                    <td class="text-primary fw-bold">{{ number_format($booking->discount_amount, 0) }}đ</td>
                                    <td>
                                        <span class="status-badge {{ $booking->status }}">
                                            @php
                                                $status_text = [
                                                    'pending' => 'Chờ xử lý',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'processing' => 'Đang thực hiện',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ][$booking->status] ?? $booking->status;
                                            @endphp
                                            {{ $status_text }}
                                        </span>
                                    </td>
                                    <td class="pe-3">{{ $booking->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-5 text-center">
                    <i class="bi bi-ticket-perforated text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3 text-muted">Chưa có lượt sử dụng nào</h4>
                    <p class="text-muted">Voucher này hiện chưa được áp dụng cho đơn hàng nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.promotions.index') }}" class="btn-admin btn-admin-secondary">
        <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
    </a>
</div>
@endsection
