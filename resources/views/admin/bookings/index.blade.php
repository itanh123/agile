@extends($baseLayout)
@section('title', 'Quản lý lịch hẹn')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Lịch hẹn</span>
@endpush

@section('content')
<div class="page-header">
    <h2>Quản lý lịch hẹn</h2>
</div>

<!-- Stats Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $statusCounts->where('status', 'pending')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Chờ xác nhận</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $statusCounts->where('status', 'confirmed')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Đã xác nhận</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $statusCounts->where('status', 'processing')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Đang thực hiện</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $statusCounts->where('status', 'completed')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Hoàn thành</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $statusCounts->where('status', 'cancelled')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Đã hủy</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center" style="background: rgba(16, 185, 129, 0.1);">
            <h3 class="mb-1" style="color: var(--admin-success);">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</h3>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">Doanh thu</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="filter-bar">
        <div class="form-group">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="customer" class="form-control-admin" placeholder="Tên khách hàng..." value="{{ request('customer') }}">
            </div>
        </div>
        <div class="form-group">
            <input type="text" name="code" class="form-control-admin" placeholder="Mã lịch hẹn..." value="{{ request('code') }}">
        </div>
        <div class="form-group">
            <select name="status" class="form-select-admin">
                <option value="">Tất cả trạng thái</option>
                @foreach(['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'processing' => 'Đang chăm sóc', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-admin btn-admin-secondary">
            <i class="bi bi-funnel"></i> Lọc
        </button>
        @if(request()->has('customer') || request()->has('code') || request()->has('status'))
            <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin-secondary">
                <i class="bi bi-x-lg"></i> Xóa lọc
            </a>
        @endif
    </form>
</div>

<!-- Bookings Table -->
<div class="admin-card">
    @if($bookings->isEmpty())
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <h4>Không tìm thấy lịch hẹn nào</h4>
            <p>Vui lòng thử lại với các tiêu chí tìm kiếm khác.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Thú cưng</th>
                        <th>Dịch vụ</th>
                        <th>Nhân viên</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th style="width: 100px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->booking_code }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="table-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                        {{ strtoupper(substr($booking->user->full_name ?? $booking->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $booking->user->full_name ?? $booking->user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $booking->pet->name ?? '-' }}</td>
                            <td>
                                @if($booking->services->count() > 0)
                                    <span class="text-primary">{{ $booking->services->count() }} dịch vụ</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($booking->staff)
                                    <span class="badge-staff" style="background: rgba(6, 182, 212, 0.15); color: var(--admin-info); padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">
                                        {{ $booking->staff->full_name ?? $booking->staff->name }}
                                    </span>
                                @else
                                    <span class="text-muted">Chưa phân công</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ number_format($booking->total_amount ?? $booking->total, 0, ',', '.') }}đ</strong>
                            </td>
                            <td>
                                <span class="status-badge {{ $booking->status }}">
                                    <i class="bi bi-{{ $booking->status === 'completed' ? 'check-circle' : ($booking->status === 'cancelled' ? 'x-circle' : ($booking->status === 'processing' ? 'gear' : 'clock')) }}"></i>
                                    {{ $booking->status_label }}
                                </span>
                            </td>
                            <td>
                                <div>{{ $booking->created_at->format('d/m/Y') }}</div>
                                @if($booking->appointment_at)
                                    <small class="text-muted">{{ $booking->appointment_at->format('H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-admin btn-admin-secondary btn-admin-sm btn-admin-icon" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <p class="text-muted mb-0" style="font-size: 0.85rem;">
                Hiển thị {{ $bookings->firstItem() ?? 0 }} đến {{ $bookings->lastItem() ?? 0 }} trong tổng số {{ $bookings->total() }} lịch hẹn
            </p>
            {{ $bookings->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
