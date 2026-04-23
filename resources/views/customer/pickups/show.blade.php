@extends('layouts.app')

@section('title', 'Chi tiết giao nhận - ' . $pickup->pickup_code)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.pickups.index') }}">Giao nhận</a></li>
            <li class="breadcrumb-item active">{{ $pickup->pickup_code }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-truck"></i> Chi tiết giao nhận</h2>
            <span class="badge bg-{{ $pickup->status_color }} fs-6">{{ $pickup->status_label }}</span>
        </div>
        @if(in_array($pickup->status, ['pending', 'assigned']))
            <form action="{{ route('customer.pickups.cancel', $pickup) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn chắc chắn muốn hủy yêu cầu giao nhận?')">
                    <i class="bi bi-x-circle"></i> Hủy yêu cầu
                </button>
            </form>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Pickup Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Thông tin giao nhận</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Địa chỉ nhận thú:</strong></p>
                            <p class="text-primary fw-bold">{{ $pickup->pickup_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Số điện thoại:</strong> {{ $pickup->pickup_phone }}</p>
                            <p><strong>Giờ hẹn nhận:</strong> {{ $pickup->scheduled_pickup_at?->format('H:i d/m/Y') }}</p>
                        </div>
                    </div>
                    @if($pickup->pickup_note)
                        <div class="alert alert-info mt-3">
                            <strong>Ghi chú của bạn:</strong><br>
                            {{ $pickup->pickup_note }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Booking Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Thông tin lịch hẹn</h5>
                </div>
                <div class="card-body">
                    <p><strong>Mã booking:</strong> {{ $pickup->booking->booking_code }}</p>
                    <p><strong>Pet:</strong> {{ $pickup->booking->pet->name }}</p>
                    <p><strong>Ngày hẹn dịch vụ:</strong> {{ $pickup->booking->appointment_at->format('H:i d/m/Y') }}</p>
                    <p><strong>Trạng thái:</strong> <span class="badge bg-{{ $pickup->booking->status_color }}">{{ $pickup->booking->status_label }}</span></p>
                </div>
            </div>

            {{-- Staff Info --}}
            @if($pickup->pickupStaff)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Nhân viên phụ trách</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <span class="text-white fw-bold">{{ substr($pickup->pickupStaff->full_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $pickup->pickupStaff->full_name }}</h6>
                                <a href="tel:{{ $pickup->pickupStaff->phone }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-telephone"></i> Gọi ngay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Tiến trình</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline list-unstyled">
                        <li class="mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2"><i class="bi bi-check"></i></span>
                                <div>
                                    <strong>Yêu cầu đã tạo</strong>
                                    <small class="d-block text-muted">{{ $pickup->created_at->format('H:i d/m/Y') }}</small>
                                </div>
                            </div>
                        </li>
                        @if($pickup->pickupStaff)
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ in_array($pickup->status, ['assigned', 'picked_up', 'delivered']) ? 'success' : 'secondary' }} me-2">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <div>
                                        <strong>Đã phân công</strong>
                                        <small class="d-block text-muted">{{ $pickup->pickupStaff->full_name }}</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                        @if($pickup->scheduled_pickup_at)
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ in_array($pickup->status, ['picked_up', 'delivered']) ? 'success' : 'warning' }} me-2">
                                        <i class="bi bi-calendar"></i>
                                    </span>
                                    <div>
                                        <strong>Giờ hẹn nhận</strong>
                                        <small class="d-block text-muted">{{ $pickup->scheduled_pickup_at->format('H:i d/m/Y') }}</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                        @if($pickup->actual_pickup_at)
                            <li class="mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2"><i class="bi bi-truck"></i></span>
                                    <div>
                                        <strong>Đã nhận thú</strong>
                                        <small class="d-block text-muted">{{ $pickup->actual_pickup_at->format('H:i d/m/Y') }}</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                        @if($pickup->delivered_at)
                            <li>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success me-2"><i class="bi bi-check-all"></i></span>
                                    <div>
                                        <strong>Đã giao trả</strong>
                                        <small class="d-block text-muted">{{ $pickup->delivered_at->format('H:i d/m/Y') }}</small>
                                    </div>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection