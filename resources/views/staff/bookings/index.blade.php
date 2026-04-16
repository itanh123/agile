@extends('staff.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Danh Sách Booking</h3>
            <p class="text-muted mb-0">Quản lý các booking được giao cho bạn.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.dashboard') }}">
            <i class="bi bi-arrow-left me-2"></i>Về Dashboard
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('staff.bookings.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tìm theo tên khách hàng</label>
                    <input type="text" name="customer" class="form-control" 
                           placeholder="Nhập tên khách hàng..." 
                           value="{{ request('customer') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày hẹn</label>
                    <input type="date" name="date" class="form-control" 
                           value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-2"></i>Tìm kiếm
                        </button>
                        <a href="{{ route('staff.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card">
        <div class="card-body p-0">
            @if($bookings->isEmpty())
                <div class="p-5 text-center">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 48px;"></i>
                    <p class="text-muted mt-3 mb-0">Không có booking nào phù hợp với bộ lọc.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Khách Hàng</th>
                                <th>Pet</th>
                                <th>Ngày Hẹn</th>
                                <th>Dịch Vụ</th>
                                <th>Thanh Toán</th>
                                <th>Trạng Thái</th>
                                <th class="text-end">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $booking->booking_code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="customer-avatar">
                                                {{ strtoupper(substr($booking->user->fullname ?? $booking->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="fw-medium">{{ $booking->user->fullname ?? $booking->user->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ $booking->user->phone ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-github text-muted"></i>
                                            {{ $booking->pet->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($booking->appointment_at)
                                            <div>{{ $booking->appointment_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $booking->appointment_at->format('H:i') }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $booking->services->count() }} dịch vụ</span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'refunded' => 'info'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $paymentColors[$booking->payment_status] ?? 'secondary' }}">
                                            @if($booking->payment_status === 'paid')
                                                <i class="bi bi-check-circle me-1"></i>
                                            @endif
                                            {{ ucfirst($booking->payment_status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'secondary',
                                                'confirmed' => 'info',
                                                'processing' => 'warning',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Chờ xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'processing' => 'Đang xử lý',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.bookings.show', $booking) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye me-1"></i>Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            Hiển thị {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} 
                            trong tổng số {{ $bookings->total() }} booking
                        </span>
                        {{ $bookings->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #3498db;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush
