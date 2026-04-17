@extends('layouts.admin')
@section('title', 'Quản lý Đơn hàng & Dịch vụ')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Quản lý Đơn hàng</span>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Theo dõi Trạng thái Dịch vụ</h2>
        <p class="text-muted small mb-0">Quản lý và cập nhật tiến độ chăm sóc thú cưng thời gian thực</p>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.location.reload()" class="btn-admin btn-admin-secondary">
            <i class="bi bi-arrow-clockwise me-2"></i> Làm mới
        </button>
    </div>
</div>

<!-- Stats Summary Dashboard -->
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <div class="admin-card text-center border-bottom border-3 border-warning">
            <div class="p-2 bg-warning bg-opacity-10 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-clock-history text-warning"></i>
            </div>
            <h3 class="mb-0 fw-bold">{{ $statusCounts->where('status', 'pending')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0 small">Chờ xử lý</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center border-bottom border-3 border-primary">
            <div class="p-2 bg-primary bg-opacity-10 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-check2-square text-primary"></i>
            </div>
            <h3 class="mb-0 fw-bold">{{ $statusCounts->where('status', 'confirmed')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0 small">Đã xác nhận</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center border-bottom border-3 border-info">
            <div class="p-2 bg-info bg-opacity-10 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-gear-wide-connected text-info"></i>
            </div>
            <h3 class="mb-0 fw-bold">{{ $statusCounts->where('status', 'processing')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0 small">Đang thực hiện</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center border-bottom border-3 border-success">
            <div class="p-2 bg-success bg-opacity-10 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-stars text-success"></i>
            </div>
            <h3 class="mb-0 fw-bold">{{ $statusCounts->where('status', 'completed')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0 small">Hoàn thành</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center border-bottom border-3 border-danger">
            <div class="p-2 bg-danger bg-opacity-10 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-x-octagon text-danger"></i>
            </div>
            <h3 class="mb-0 fw-bold">{{ $statusCounts->where('status', 'cancelled')->first()->total ?? 0 }}</h3>
            <p class="text-muted mb-0 small">Đã hủy</p>
        </div>
    </div>
    <div class="col-md-2">
        <div class="admin-card text-center bg-primary bg-opacity-10 border-0">
            <div class="p-2 bg-primary bg-opacity-20 rounded-circle mx-auto mb-2" style="width: 40px; height: 40px;">
                <i class="bi bi-currency-dollar text-primary"></i>
            </div>
            <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalRevenue ?? 0, 0) }}đ</h3>
            <p class="text-primary mb-0 small fw-bold">Doanh thu (Tháng)</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-4 shadow-sm border-0">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="customer" class="form-control-admin border-start-0" placeholder="Tìm theo tên khách hàng..." value="{{ request('customer') }}">
            </div>
        </div>
        <div class="col-md-3">
            <input type="text" name="code" class="form-control-admin" placeholder="Mã đơn hàng (BK-...)" value="{{ request('code') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select-admin">
                <option value="">-- Tất cả trạng thái --</option>
                @php
                    $status_map = [
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'processing' => 'Đang thực hiện',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy'
                    ];
                @endphp
                @foreach($status_map as $key => $val)
                    <option value="{{ $key }}" @selected(request('status') == $key)>{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn-admin btn-admin-primary flex-fill">
                <i class="bi bi-funnel me-1"></i> Lọc
            </button>
            @if(request()->anyFilled(['customer', 'code', 'status']))
                <a href="{{ route('admin.bookings.index') }}" class="btn-outline-admin text-danger text-center" style="width: 40px; padding-top: 8px;">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Bookings Table Dashboard -->
<div class="admin-card p-0 overflow-hidden shadow-sm border-0">
    @if($bookings->isEmpty())
        <div class="empty-state py-5 text-center">
            <div class="p-4 bg-light rounded-circle d-inline-block mb-3">
                <i class="bi bi-calendar-x fs-1 text-muted"></i>
            </div>
            <h4 class="text-muted">Không tìm thấy đơn hàng nào</h4>
            <p class="text-muted small">Thử thay đổi điều kiện lọc hoặc từ khóa tìm kiếm.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Mã Đơn</th>
                        <th>Khách hàng</th>
                        <th>Thú cưng</th>
                        <th class="text-center">Số DV</th>
                        <th>Nhân viên</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày hẹn</th>
                        <th class="text-end pe-4">Thao tác nhanh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="align-middle border-top border-light {{ $booking->status === 'processing' ? 'bg-info bg-opacity-5' : '' }}">
                            <td class="ps-4">
                                <span class="fw-bold text-dark">{{ $booking->booking_code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($booking->user->full_name ?? $booking->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="fw-medium">{{ $booking->user->full_name ?? $booking->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark fw-normal px-2 py-1">
                                    {{ $booking->pet->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ $booking->services->count() }}</span>
                            </td>
                            <td>
                                @if($booking->staff)
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-person-badge text-info"></i>
                                        <span class="small">{{ $booking->staff->full_name ?? $booking->staff->name }}</span>
                                    </div>
                                @else
                                    <span class="text-danger x-small fw-bold"><i class="bi bi-patch-question me-1"></i>Chưa giao</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($booking->total_amount, 0) }}đ</span>
                            </td>
                            <td>
                                <span class="status-badge {{ $booking->status }} x-small px-2 py-1">
                                    @php
                                        echo $status_map[$booking->status] ?? $booking->status;
                                    @endphp
                                </span>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $booking->appointment_at->format('d/m/Y') }}</div>
                                <div class="text-muted x-small">{{ $booking->appointment_at->format('H:i') }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-light border" title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Xác nhận" onclick="return confirm('Xác nhận đặt lịch này?')">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($booking->status === 'confirmed')
                                        <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="processing">
                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Bắt đầu làm">
                                                <i class="bi bi-play-fill"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($booking->status === 'processing')
                                        <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Hoàn thành">
                                                <i class="bi bi-check-all"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-light border-top d-flex justify-content-between align-items-center">
            <p class="text-muted mb-0 small">
                Hiển thị {{ $bookings->firstItem() ?? 0 }} đến {{ $bookings->lastItem() ?? 0 }} của {{ $bookings->total() }} đơn hàng
            </p>
            <div>
                {{ $bookings->withQueryString()->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
