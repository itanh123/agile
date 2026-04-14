@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Staff Dashboard</h3>
            <p class="text-muted mb-0">Xem nhanh các booking được giao và trạng thái công việc.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('staff.bookings.index') }}">Xem tất cả booking</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Tổng booking</h6>
                    <p class="display-6 mb-0">{{ $totalBookings }}</p>
                    <p class="text-muted mb-0">10 booking mới nhất được giao cho bạn.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Đang xử lý</h6>
                    <p class="display-6 mb-0">{{ $processingBookings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Chờ xác nhận / hoàn thành</h6>
                    <p class="display-6 mb-0">{{ $activeBookings }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Booking được giao</div>
        <div class="card-body p-0">
            @if($bookings->isEmpty())
                <div class="p-4 text-center text-muted">Bạn chưa có booking được giao.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Khách</th>
                                <th>Pet</th>
                                <th>Appointment</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->booking_code }}</td>
                                    <td>{{ $booking->user->full_name ?? $booking->user->name }}</td>
                                    <td>{{ $booking->pet->name ?? '-' }}</td>
                                    <td>{{ optional($booking->appointment_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'processing' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td><a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
