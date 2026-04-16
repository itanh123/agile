@extends('staff.layout')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Chào mừng, {{ auth()->user()->fullname ?? auth()->user()->name }}!</h3>
            <p class="text-muted mb-0">Đây là tổng quan công việc của bạn hôm nay.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('staff.bookings.index') }}">
            <i class="bi bi-list-ul me-2"></i>Xem tất cả booking
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100" style="border-top: 4px solid #3498db;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Tổng Booking</h6>
                            <p class="display-5 mb-0 fw-bold text-primary">{{ $totalBookings }}</p>
                        </div>
                        <div class="stat-icon" style="background: rgba(52, 152, 219, 0.15); color: #3498db;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0 small">Tất cả booking được giao</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100" style="border-top: 4px solid #f39c12;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Đang Xử Lý</h6>
                            <p class="display-5 mb-0 fw-bold" style="color: #f39c12;">{{ $processingBookings }}</p>
                        </div>
                        <div class="stat-icon" style="background: rgba(243, 156, 18, 0.15); color: #f39c12;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0 small">Đang thực hiện dịch vụ</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100" style="border-top: 4px solid #2ecc71;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Hoàn Thành Hôm Nay</h6>
                            <p class="display-5 mb-0 fw-bold" style="color: #2ecc71;">{{ $completedToday }}</p>
                        </div>
                        <div class="stat-icon" style="background: rgba(46, 204, 113, 0.15); color: #2ecc71;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0 small">Đã hoàn thành hôm nay</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card h-100" style="border-top: 4px solid #e74c3c;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-muted mb-2">Đã Hủy</h6>
                            <p class="display-5 mb-0 fw-bold" style="color: #e74c3c;">{{ $cancelledBookings }}</p>
                        </div>
                        <div class="stat-icon" style="background: rgba(231, 76, 60, 0.15); color: #e74c3c;">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0 small">Số booking đã hủy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="{{ route('staff.bookings.index') }}" class="quick-action-card">
                <i class="bi bi-calendar-plus"></i>
                <h6 class="mb-1">Xem Tất Cả Booking</h6>
                <p class="text-muted mb-0 small">Danh sách đầy đủ các công việc</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('staff.bookings.index', ['status' => 'processing']) }}" class="quick-action-card">
                <i class="bi bi-arrow-repeat"></i>
                <h6 class="mb-1">Cập Nhật Trạng Thái</h6>
                <p class="text-muted mb-0 small">Cập nhật tiến độ booking</p>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('staff.bookings.index') }}" class="quick-action-card">
                <i class="bi bi-camera"></i>
                <h6 class="mb-1">Tải Lên Hình Ảnh</h6>
                <p class="text-muted mb-0 small">Đăng hình tiến độ cho khách</p>
            </a>
        </div>
    </div>

    <!-- Today's Bookings -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-calendar-event me-2 text-primary"></i>Booking Hôm Nay
            </h5>
            <span class="badge bg-primary">{{ $todayBookings->count() }} booking</span>
        </div>
        <div class="card-body p-0">
            @if($todayBookings->isEmpty())
                <div class="p-5 text-center">
                    <i class="bi bi-calendar-x text-muted" style="font-size: 48px;"></i>
                    <p class="text-muted mt-3 mb-0">Không có booking nào được lên lịch hôm nay.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Khách Hàng</th>
                                <th>Pet</th>
                                <th>Giờ Hẹn</th>
                                <th>Dịch Vụ</th>
                                <th>Trạng Thái</th>
                                <th class="text-end">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todayBookings as $booking)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $booking->booking_code }}</span>
                                    </td>
                                    <td>{{ $booking->user->fullname ?? $booking->user->name }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-github text-muted"></i>
                                            {{ $booking->pet->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($booking->appointment_at)
                                            {{ $booking->appointment_at->format('H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $booking->services->count() }} dịch vụ</span>
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
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2 text-primary"></i>Booking Gần Đây
            </h5>
        </div>
        <div class="card-body p-0">
            @if($recentBookings->isEmpty())
                <div class="p-4 text-center text-muted">Chưa có booking nào.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Khách Hàng</th>
                                <th>Pet</th>
                                <th>Ngày Hẹn</th>
                                <th>Trạng Thái</th>
                                <th class="text-end">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentBookings as $booking)
                                <tr>
                                    <td class="fw-bold">{{ $booking->booking_code }}</td>
                                    <td>{{ $booking->user->fullname ?? $booking->user->name }}</td>
                                    <td>{{ $booking->pet->name ?? '-' }}</td>
                                    <td>
                                        @if($booking->appointment_at)
                                            {{ $booking->appointment_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
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
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
