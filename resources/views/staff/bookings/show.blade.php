@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Booking {{ $booking->booking_code }}</h3>
            <p class="text-muted mb-0">Chi tiết công việc và các thao tác cần thiết cho nhân viên.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.bookings.index') }}">Quay lại danh sách</a>
    </div>

    <div class="row gy-4">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thông tin booking</h5>
                    <div class="row">
                        <div class="col-sm-6 mb-3"><strong>Khách hàng</strong><div>{{ $booking->user->full_name ?? $booking->user->name }}</div></div>
                        <div class="col-sm-6 mb-3"><strong>Pet</strong><div>{{ $booking->pet->name ?? '-' }}</div></div>
                        <div class="col-sm-6 mb-3"><strong>Appointment</strong><div>{{ optional($booking->appointment_at)->format('d/m/Y H:i') ?? '-' }}</div></div>
                        <div class="col-sm-6 mb-3"><strong>Payment</strong><div>{{ ucfirst($booking->payment_status ?? 'pending') }}</div></div>
                        <div class="col-sm-6 mb-3"><strong>Service mode</strong><div>{{ ucfirst($booking->service_mode ?? '-')}}</div></div>
                        <div class="col-sm-6 mb-3"><strong>Tổng giá</strong><div>{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} đ</div></div>
                        <div class="col-12 mb-3"><strong>Ghi chú khách</strong><div class="text-break">{{ $booking->note ?? '-' }}</div></div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">Trạng thái hiện tại</h6>
                            <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'processing' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} fs-6">{{ ucfirst($booking->status) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">Hình ảnh tiến độ</h6>
                            <p class="mb-0">{{ $booking->images->count() }} ảnh đã đăng</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Dịch vụ</h5>
                    @if($booking->services->isEmpty())
                        <div class="text-muted">Chưa có dịch vụ nào được chọn.</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($booking->services as $service)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $service->name }}</span>
                                    <span class="text-muted">x{{ $service->pivot->quantity }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Lịch sử và ghi chú</h5>
                    @if($booking->logs->isEmpty())
                        <div class="text-muted">Chưa có ghi chú hay thay đổi trạng thái nào.</div>
                    @else
                        <div class="timeline">
                            @foreach($booking->logs as $log)
                                <div class="mb-3">
                                    <div><strong>{{ $log->changed_at->format('d/m/Y H:i') }}</strong> - <span>{{ ucfirst($log->status) }}</span></div>
                                    <div class="text-muted">{{ $log->note }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Cập nhật trạng thái</h5>
                    <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                @foreach(['confirmed', 'processing', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú cập nhật</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Lý do hoặc tình trạng cụ thể"></textarea>
                        </div>
                        <button class="btn btn-primary w-100">Cập nhật</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Thêm hình ảnh tiến độ</h5>
                    <form method="POST" action="{{ route('staff.bookings.upload-image', $booking) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Đường dẫn ảnh</label>
                            <input name="image_path" class="form-control" placeholder="storage/pets/anh1.jpg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giai đoạn</label>
                            <input name="stage" class="form-control" placeholder="Before / During / After">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <input name="caption" class="form-control" placeholder="Mô tả ngắn về ảnh">
                        </div>
                        <button class="btn btn-success w-100">Thêm ảnh</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ghi chú mới</h5>
                    <form method="POST" action="{{ route('staff.bookings.add-note', $booking) }}">
                        @csrf
                        <div class="mb-3">
                            <textarea name="note" class="form-control" rows="4" placeholder="Ghi chú nội bộ cho booking"></textarea>
                        </div>
                        <button class="btn btn-secondary w-100">Lưu ghi chú</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
