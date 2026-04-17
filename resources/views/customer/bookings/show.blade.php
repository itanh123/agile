@extends('layouts.app')
@section('title', 'Theo dõi đơn hàng ' . $booking->booking_code)

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5 animate-fade-up">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('customer.bookings.index') }}" class="text-primary text-decoration-none">Đơn hàng của tôi</a></li>
                    <li class="breadcrumb-item active text-white-50" aria-current="page">{{ $booking->booking_code }}</li>
                </ol>
            </nav>
            <h1 class="text-white fw-bold mb-0">Mã đơn: <span class="text-primary">{{ $booking->booking_code }}</span></h1>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(!in_array($booking->status, ['completed', 'cancelled']))
                <button type="button" class="btn-outline-premium py-2 px-4" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                    <i class="bi bi-calendar-event me-2"></i>Đổi lịch hẹn
                </button>
                <button type="button" class="btn-outline-premium border-danger text-danger py-2 px-4" data-bs-toggle="modal" data-bs-target="#cancelModal">
                    <i class="bi bi-x-circle me-2"></i>Hủy đơn
                </button>
            @endif
        </div>
    </div>

    <!-- Status Tracker Horizontal -->
    <div class="glass-card p-4 mb-5 animate-fade-up" style="--delay: 0.1s">
        <div class="status-tracker-wrapper">
            @php
                $statuses = [
                    'pending' => ['label' => 'Đã đặt', 'icon' => 'bi-calendar-check'],
                    'confirmed' => ['label' => 'Đã xác nhận', 'icon' => 'bi-check-circle'],
                    'processing' => ['label' => 'Đang thực hiện', 'icon' => 'bi-gear-wide-connected'],
                    'completed' => ['label' => 'Hoàn thành', 'icon' => 'bi-stars']
                ];
                $currentIndex = array_search($booking->status, array_keys($statuses));
                if ($booking->status === 'cancelled') $currentIndex = -1;
            @endphp

            @if($booking->status === 'cancelled')
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-4 d-flex align-items-center p-4">
                    <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Đơn hàng này đã bị hủy</h5>
                        <p class="mb-0 small opacity-75">Bạn có thể liên hệ với chúng tôi nếu có bất kỳ thắc mắc nào.</p>
                    </div>
                </div>
            @else
                <div class="status-steps">
                    @foreach($statuses as $key => $step)
                        @php
                            $stepIndex = array_search($key, array_keys($statuses));
                            $isActive = $stepIndex <= $currentIndex;
                            $isCurrent = $key === $booking->status;
                        @endphp
                        <div class="step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                            <div class="step-icon">
                                <i class="bi {{ $step['icon'] }}"></i>
                            </div>
                            <span class="step-label">{{ $step['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Services Card -->
            <div class="glass-card p-4 mb-4 animate-fade-up" style="--delay: 0.2s">
                <h5 class="text-white fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-box-seam text-primary me-2"></i>Dịch vụ đã chọn
                </h5>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small text-uppercase">
                            <tr>
                                <th>Tên dịch vụ</th>
                                <th class="text-end">Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->services as $service)
                            <tr class="border-bottom border-white border-opacity-5">
                                <td class="py-3">
                                    <div class="text-white fw-medium">{{ $service->name }}</div>
                                    <div class="text-muted small">Chăm sóc thú cưng chuyên nghiệp</div>
                                </td>
                                <td class="text-end text-primary fw-bold">{{ number_format($service->price, 0) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-4 rounded-4" style="background: rgba(255,255,255,0.03)">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="text-white">{{ number_format($booking->subtotal, 0) }}đ</span>
                    </div>
                    @if($booking->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Giảm giá:</span>
                        <span class="text-primary">-{{ number_format($booking->discount_amount, 0) }}đ</span>
                    </div>
                    @endif
                    <hr class="border-white border-opacity-10 my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-white fw-bold fs-5">Tổng thanh toán:</span>
                        <span class="text-primary fw-bold fs-3">{{ number_format($booking->total_amount, 0) }}đ</span>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="glass-card p-4 animate-fade-up" style="--delay: 0.3s">
                <h5 class="text-white fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-clock-history text-primary me-2"></i>Lịch sử trạng thái
                </h5>
                <div class="vertical-timeline px-3">
                    @foreach($booking->logs->sortByDesc('created_at') as $log)
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $log->status }}"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="badge {{ $log->status }}-badge text-uppercase x-small" style="font-size: 0.65rem;">
                                        @php
                                            $st_map = ['pending'=>'Đã đặt', 'confirmed'=>'Xác nhận', 'processing'=>'Đang làm', 'completed'=>'Hoàn thành', 'cancelled'=>'Đã hủy'];
                                            echo $st_map[$log->status] ?? $log->status;
                                        @endphp
                                    </span>
                                    <span class="text-muted x-small">{{ $log->created_at->format('H:i, d/m/Y') }}</span>
                                </div>
                                <p class="text-white-50 small mb-0">{{ $log->note }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="col-lg-4">
            <!-- Appointment & Pet Card -->
            <div class="glass-card p-4 mb-4 animate-fade-up" style="--delay: 0.4s">
                <h6 class="text-muted small fw-bold text-uppercase mb-4">Thông tin cơ bản</h6>
                
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-calendar-event fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted x-small mb-1">Thời gian hẹn</div>
                        <div class="text-white fw-bold">{{ $booking->appointment_at->format('d \t\h\á\n\g m, Y') }}</div>
                        <div class="text-primary small fw-bold">{{ $booking->appointment_at->format('H:i') }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-2 rounded-3 bg-info bg-opacity-10 text-info">
                        <i class="bi bi-heart-pulse fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted x-small mb-1">Thú cưng</div>
                        <div class="text-white fw-bold">{{ $booking->pet->name ?? 'Không xác định' }}</div>
                        <div class="text-muted small">{{ $booking->pet->species ?? '' }} ({{ $booking->pet->breed ?? '' }})</div>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3">
                    <div class="p-2 rounded-3 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted x-small mb-1">Thanh toán</div>
                        <div class="text-white fw-bold">{{ strtoupper($booking->payment_method) }}</div>
                        <span class="badge {{ $booking->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }} x-small">
                            {{ $booking->payment_status === 'paid' ? 'Đã thu' : 'Chưa thanh toán' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Photos From Staff -->
            <div class="glass-card p-4 animate-fade-up" style="--delay: 0.5s">
                <h6 class="text-muted small fw-bold text-uppercase mb-4">Hình ảnh quá trình</h6>
                @if($booking->images->isNotEmpty())
                    <div class="row g-2">
                        @foreach($booking->images as $image)
                        <div class="col-6">
                            <div class="rounded-3 overflow-hidden border border-white border-opacity-10">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid" alt="Progress">
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 bg-white bg-opacity-5 rounded-4">
                        <i class="bi bi-images text-muted fs-2"></i>
                        <p class="text-muted small mt-2 mb-0">Chưa có ảnh được cập nhật</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0" style="background: rgba(23,23,35,0.95); backdrop-filter: blur(20px);">
            <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                <h5 class="modal-title text-white fw-bold">Thay Đổi Lịch Hẹn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customer.bookings.reschedule', $booking) }}">
                @csrf @method('PATCH')
                <div class="modal-body p-4 text-center">
                    <p class="text-muted">Chọn thời gian mới cho lịch hẹn của bạn.</p>
                    <input type="datetime-local" name="appointment_at" class="form-control-premium w-100" required>
                </div>
                <div class="modal-footer border-top border-white border-opacity-10 p-3">
                    <button type="button" class="btn text-white opacity-50" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn-premium px-4 py-2">Xác nhận đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0" style="background: rgba(23,23,35,0.95); backdrop-filter: blur(20px);">
            <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                <h5 class="modal-title text-white fw-bold text-danger">Xác Nhận Hủy Đơn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">
                @csrf @method('PATCH')
                <div class="modal-body p-4 text-center">
                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-block mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-trash fs-3"></i>
                    </div>
                    <p class="text-white">Bạn có chắc chắn muốn hủy đơn hàng <span class="fw-bold">{{ $booking->booking_code }}</span>?</p>
                    <p class="text-muted small">Hành động này không thể hoàn tác.</p>
                </div>
                <div class="modal-footer border-top border-white border-opacity-10 p-3">
                    <button type="button" class="btn text-white opacity-50" data-bs-dismiss="modal">Quay lại</button>
                    <button type="submit" class="btn btn-danger px-4 py-2 rounded-3 border-0">Hủy đơn ngay</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Status Tracker Styles */
.status-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    padding-bottom: 20px;
}
.status-steps::before {
    content: '';
    position: absolute;
    top: 25px;
    left: 10%;
    right: 10%;
    height: 3px;
    background: rgba(255,255,255,0.1);
    z-index: 1;
}
.step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}
.step-icon {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: #1e1e2d;
    border: 2px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: rgba(255,255,255,0.3);
    margin-bottom: 12px;
    transition: all 0.5s ease;
}
.step-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: rgba(255,255,255,0.3);
    transition: all 0.5s ease;
}
.step.active .step-icon {
    border-color: var(--primary);
    color: var(--primary);
    box-shadow: 0 0 20px rgba(99,102,241,0.3);
}
.step.active .step-label {
    color: var(--primary);
}
.step.current .step-icon {
    background: var(--primary);
    color: white;
}
.step.active::after {
    content: '';
    position: absolute;
    top: 25px;
    left: -50%;
    width: 100%;
    height: 3px;
    background: var(--primary);
    z-index: -1;
}
.step:first-child.active::after { display: none; }

/* Timeline Styles */
.vertical-timeline {
    position: relative;
    padding-left: 20px;
    border-left: 1px dashed rgba(255,255,255,0.1);
}
.timeline-item {
    position: relative;
    padding-bottom: 25px;
}
.timeline-marker {
    position: absolute;
    left: -25.5px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: var(--primary);
    border: 2px solid #1e1e2d;
}
.timeline-marker.pending { background: #6366f1; }
.timeline-marker.confirmed { background: #10b981; }
.timeline-marker.processing { background: #f59e0b; }
.timeline-marker.completed { background: #8b5cf6; }
.timeline-marker.cancelled { background: #ef4444; }

.x-small { font-size: 0.75rem; }

.pending-badge { background: rgba(99,102,241,0.1); color: #6366f1; }
.confirmed-badge { background: rgba(16,185,129,0.1); color: #10b981; }
.processing-badge { background: rgba(245,158,11,0.1); color: #f59e0b; }
.completed-badge { background: rgba(139,92,246,0.1); color: #8b5cf6; }
.cancelled-badge { background: rgba(239,68,68,0.1); color: #ef4444; }
</style>
@endsection
