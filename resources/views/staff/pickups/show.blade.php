@extends('staff.layout')

@section('title', 'Chi tiết giao nhận')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-truck"></i> Chi tiết giao nhận</h2>
            <span class="badge bg-{{ $pickup->status_color }} fs-6">{{ $pickup->pickup_code }}</span>
        </div>
        <a href="{{ route('staff.pickups.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Booking Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-receipt"></i> Thông tin booking</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã booking:</strong> {{ $pickup->booking->booking_code }}</p>
                            <p><strong>Khách hàng:</strong> {{ $pickup->booking->user->full_name }}</p>
                            <p><strong>SDT khách:</strong> {{ $pickup->booking->user->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Pet:</strong> {{ $pickup->booking->pet->name }}</p>
                            <p><strong>Ngày hẹn:</strong> {{ $pickup->booking->appointment_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái booking:</strong> <span class="badge bg-{{ $pickup->booking->status_color }}">{{ $pickup->booking->status_label }}</span></p>
                        </div>
                    </div>
                    <hr>
                    <h6>Dịch vụ đã đặt:</h6>
                    <ul>
                        @foreach($pickup->booking->services as $service)
                            <li>{{ $service->name }} - {{ number_format($service->pivot->unit_price, 0, ',', '.') }}đ</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Pickup Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Thông tin giao nhận</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Địa chỉ nhận:</strong></p>
                            <p class="text-primary fw-bold">{{ $pickup->pickup_address }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $pickup->pickup_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giờ hẹn nhận:</strong> {{ $pickup->scheduled_pickup_at?->format('H:i d/m/Y') }}</p>
                            <p><strong>Giờ nhận thực tế:</strong> {{ $pickup->actual_pickup_at?->format('H:i d/m/Y') ?: '-' }}</p>
                            <p><strong>Giờ giao trả:</strong> {{ $pickup->delivered_at?->format('H:i d/m/Y') ?: '-' }}</p>
                        </div>
                    </div>
                    @if($pickup->pickup_note)
                        <div class="alert alert-info mt-3">
                            <strong>Ghi chú khách hàng:</strong><br>
                            {{ $pickup->pickup_note }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Staff Notes --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-journal-text"></i> Ghi chú nhân viên</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('staff.pickups.add-note', $pickup) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="staff_notes" class="form-control" rows="3" placeholder="Nhập ghi chú của bạn...">{{ $pickup->staff_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu ghi chú
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    @if($pickup->status === 'pending')
                        <form action="{{ route('staff.pickups.accept', $pickup) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Giờ hẹn nhận:</label>
                                <input type="datetime-local" name="scheduled_pickup_at" class="form-control" value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Nhận yêu cầu
                            </button>
                        </form>
                    @endif

                    @if($pickup->pickup_staff_id === auth()->id() && $pickup->status === 'assigned')
                        <form action="{{ route('staff.pickups.picked-up', $pickup) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bi bi-truck"></i> Đã nhận thú cưng
                            </button>
                        </form>
                    @endif

                    @if($pickup->pickup_staff_id === auth()->id() && $pickup->status === 'picked_up')
                        <form action="{{ route('staff.pickups.delivered', $pickup) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-all"></i> Đã giao trả
                            </button>
                        </form>
                    @endif

                    <hr>

                    <a href="tel:{{ $pickup->pickup_phone }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-telephone"></i> Gọi khách
                    </a>
                    <a href="tel:{{ $pickup->booking->user->phone }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-person"></i> Liên hệ chủ pet
                    </a>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline list-unstyled">
                        <li class="mb-3">
                            <span class="badge bg-success"><i class="bi bi-check"></i></span>
                            <span class="ms-2">Tạo yêu cầu: {{ $pickup->created_at->format('H:i d/m') }}</span>
                        </li>
                        @if($pickup->pickupStaff)
                            <li class="mb-3">
                                <span class="badge bg-info"><i class="bi bi-person"></i></span>
                                <span class="ms-2">Nhân viên: {{ $pickup->pickupStaff->full_name }}</span>
                            </li>
                        @endif
                        @if($pickup->scheduled_pickup_at)
                            <li class="mb-3">
                                <span class="badge bg-warning"><i class="bi bi-calendar"></i></span>
                                <span class="ms-2">Giờ hẹn: {{ $pickup->scheduled_pickup_at->format('H:i d/m') }}</span>
                            </li>
                        @endif
                        @if($pickup->actual_pickup_at)
                            <li class="mb-3">
                                <span class="badge bg-primary"><i class="bi bi-truck"></i></span>
                                <span class="ms-2">Đã nhận: {{ $pickup->actual_pickup_at->format('H:i d/m') }}</span>
                            </li>
                        @endif
                        @if($pickup->delivered_at)
                            <li>
                                <span class="badge bg-success"><i class="bi bi-check-all"></i></span>
                                <span class="ms-2">Giao trả: {{ $pickup->delivered_at->format('H:i d/m') }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection