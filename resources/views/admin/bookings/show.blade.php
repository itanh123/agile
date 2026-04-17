@extends('layouts.admin')
@section('title', 'Chi tiết Đơn hàng: ' . $booking->booking_code)

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.bookings.index') }}">Đơn hàng</a>
<i class="bi bi-chevron-right"></i>
<span>{{ $booking->booking_code }}</span>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Booking Info -->
        <div class="admin-card mb-4 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 class="mb-1 fw-bold">Thông tin dịch vụ</h4>
                    <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 700;">
                        {{ $booking->booking_code }}
                    </span>
                </div>
                <div class="text-end">
                    <span class="status-badge {{ $booking->status }} fs-6 px-3 py-2">
                        @php
                            $st_map = ['pending'=>'Chờ xử lý', 'confirmed'=>'Đã xác nhận', 'processing'=>'Đang thực hiện', 'completed'=>'Hoàn thành', 'cancelled'=>'Đã hủy'];
                            echo $st_map[$booking->status] ?? $booking->status;
                        @endphp
                    </span>
                    <div class="text-muted small mt-1">Cập nhật: {{ $booking->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border border-white border-opacity-10">
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Khách hàng</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="background: var(--admin-primary); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                {{ substr($booking->user->full_name ?? $booking->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold">{{ $booking->user->full_name ?? $booking->user->name }}</div>
                                <div class="text-muted small">{{ $booking->user->email }}</div>
                                <div class="text-muted small">{{ $booking->user->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light border border-white border-opacity-10">
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Thú cưng</h6>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle" style="background: var(--admin-info); color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                                <i class="bi bi-heart-pulse"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $booking->pet->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $booking->pet->species ?? '-' }} | {{ $booking->pet->breed ?? '-' }}</div>
                                <div class="text-muted small">Cân nặng: {{ $booking->pet->weight ?? '-' }}kg</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                <div class="row">
                    <div class="col-md-4 border-end border-primary border-opacity-10">
                        <div class="text-muted small mb-1">Thời gian tạo đơn</div>
                        <div class="fw-bold">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="col-md-4 border-end border-primary border-opacity-10">
                        <div class="text-muted small mb-1">Thời gian hẹn</div>
                        <div class="fw-bold text-primary">{{ $booking->appointment_at ? $booking->appointment_at->format('d/m/Y H:i') : '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small mb-1">Hình thức</div>
                        <div class="fw-bold">{{ $booking->service_mode === 'at_store' ? 'Tại cửa hàng' : 'Tại nhà' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Services -->
        <div class="admin-card mb-4 p-0 overflow-hidden shadow-sm">
            <div class="admin-card-header bg-light p-3 border-bottom">
                <h5 class="admin-card-title mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Danh sách dịch vụ</h5>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Tên dịch vụ</th>
                            <th>Thời lượng</th>
                            <th class="text-end pe-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($booking->services as $service)
                            <tr class="border-top border-light">
                                <td class="ps-4"><strong>{{ $service->name }}</strong></td>
                                <td>{{ $service->duration_minutes ?? 30 }} phút</td>
                                <td class="text-end pe-4 fw-bold text-primary">{{ number_format($service->price, 0) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="admin-card mb-4 shadow-sm">
            <h5 class="admin-card-title mb-4">Chi tiết thanh toán</h5>
            <div class="row g-4 justify-content-end">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính (Services):</span>
                            <span class="fw-bold">{{ number_format($booking->subtotal, 0) }}đ</span>
                        </div>
                        @if($booking->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <span class="text-muted">Giảm giá ({{ $booking->promotion->code ?? 'Voucher' }}):</span>
                            <span class="fw-bold">-{{ number_format($booking->discount_amount, 0) }}đ</span>
                        </div>
                        @endif
                        <hr class="my-2 border-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Tổng số tiền:</span>
                            <span class="fw-bold fs-4 text-primary">{{ number_format($booking->total_amount, 0) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Timeline Logs -->
        <div class="admin-card mb-4 shadow-sm">
            <h5 class="admin-card-title mb-4">Lịch sử hoạt động (Service Logs)</h5>
            <div class="vertical-timeline ps-3 border-start border-2 border-light ms-2">
                @foreach($booking->logs->sortByDesc('created_at') as $log)
                    <div class="timeline-item mb-4 position-relative ps-4">
                        <div class="timeline-dot position-absolute start-0 translate-middle-x rounded-circle {{ $log->status }}" style="width: 12px; height: 12px; margin-left: -2px; top: 5px;"></div>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="badge {{ $log->status }}-badge text-uppercase x-small">
                                @php
                                    echo $st_map[$log->status] ?? $log->status;
                                @endphp
                            </span>
                            <span class="text-muted small">{{ $log->created_at->format('H:i, d/m/Y') }}</span>
                        </div>
                        <p class="mb-0 text-muted small">{{ $log->note }}</p>
                        @if($log->changedBy)
                        <div class="text-muted x-small mt-1">Thực hiện bởi: {{ $log->changedBy->full_name ?? $log->changedBy->name }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Customer Review -->
        @if($booking->review)
        <div class="admin-card shadow-sm border-0 border-top border-5 border-warning">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="admin-card-title mb-0">Đánh giá từ khách hàng</h5>
                <div class="text-warning">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= $booking->review->rating ? '-fill' : '' }} fs-5"></i>
                    @endfor
                </div>
            </div>
            <div class="p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-10">
                <h6 class="fw-bold mb-2">{{ $booking->review->title }}</h6>
                <p class="mb-0 text-muted italic">"{{ $booking->review->comment }}"</p>
                <div class="mt-2 text-muted x-small">Đã gửi vào: {{ $booking->review->created_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Quick Status Update -->
        <div class="admin-card mb-4 shadow-sm border-0 bg-primary bg-opacity-10">
            <h5 class="admin-card-title mb-4 text-primary"><i class="bi bi-lightning-fill me-2"></i>Trạng thái nhanh</h5>
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf @method('PATCH')
                <div class="d-flex flex-column gap-2">
                    @if($booking->status === 'pending')
                    <button type="submit" name="status" value="confirmed" class="btn btn-primary py-2 rounded-3 text-start px-3">
                        <i class="bi bi-check2-circle me-2"></i> Xác nhận lịch hẹn này
                    </button>
                    @endif

                    @if($booking->status === 'confirmed')
                    <button type="submit" name="status" value="processing" class="btn btn-warning py-2 rounded-3 text-start px-3 text-white">
                        <i class="bi bi-gear-wide-connected me-2"></i> Đang chăm sóc thú cưng
                    </button>
                    @endif

                    @if($booking->status === 'processing')
                    <button type="submit" name="status" value="completed" class="btn btn-success py-2 rounded-3 text-start px-3">
                        <i class="bi bi-star-fill me-2"></i> Hoàn thành dịch vụ
                    </button>
                    @endif

                    @if(!in_array($booking->status, ['completed', 'cancelled']))
                    <button type="button" class="btn border-danger text-danger py-2 rounded-3 text-start px-3 mt-4" data-bs-toggle="modal" data-bs-target="#adminCancelModal">
                        <i class="bi bi-x-octagon me-2"></i> Hủy đơn hàng này
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Assign Staff -->
        <div class="admin-card mb-4 shadow-sm">
            <h5 class="admin-card-title mb-3">Phân công nhân viên</h5>
            @if(isset($staffUsers) && $staffUsers->isNotEmpty())
                <form method="POST" action="{{ route('admin.bookings.assign-staff', $booking) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="staff_id" class="form-select-admin">
                            <option value="">Chọn nhân viên...</option>
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->id }}" @selected($booking->staff_id == $staff->id)>
                                    {{ $staff->full_name ?? $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary w-100">
                        <i class="bi bi-person-check me-2"></i> Giao việc ngay
                    </button>
                </form>
            @else
                <p class="text-muted mb-0 small">Chưa có nhân viên nào.</p>
            @endif
        </div>
        
        <!-- Detailed Update -->
        <div class="admin-card mb-4 shadow-sm border-0 border-top border-5 border-warning">
            <h5 class="admin-card-title mb-3 text-warning">Chi tiết bổ sung</h5>
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Trạng thái thủ công</label>
                    <select name="status" class="form-select-admin">
                        @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($booking->status == $status)>
                                @php echo $st_map[$status] ?? $status; @endphp
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Ghi chú từ admin</label>
                    <textarea name="note" class="form-control-admin" rows="2" placeholder="Nhập ghi chú quan trọng...">{{ $booking->note ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100 py-2 border-0 shadow-sm text-white">
                    <i class="bi bi-save me-2"></i> Cập nhật chi tiết
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Admin Cancel Modal -->
<div class="modal fade" id="adminCancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 overflow-hidden rounded-4">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold">Xác nhận Hủy Đơn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <div class="modal-body p-4">
                    <p>Hành động này sẽ hủy vĩnh viễn đơn hàng <strong>{{ $booking->booking_code }}</strong> và thông báo cho khách hàng.</p>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Lý do hủy (Yêu cầu)</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Nhập lý do gửi đến khách hàng..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-secondary border-0 px-4" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-danger border-0 px-4">Xác nhận Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.pending-badge { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
.confirmed-badge { background: rgba(16, 185, 129, 0.1); color: #10b981; }
.processing-badge { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
.completed-badge { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
.cancelled-badge { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

.timeline-dot.pending { background: #6366f1; box-shadow: 0 0 10px #6366f188; }
.timeline-dot.confirmed { background: #10b981; box-shadow: 0 0 10px #10b98188; }
.timeline-dot.processing { background: #f59e0b; box-shadow: 0 0 10px #f59e0b88; }
.timeline-dot.completed { background: #8b5cf6; box-shadow: 0 0 10px #8b5cf688; }
.timeline-dot.cancelled { background: #ef4444; box-shadow: 0 0 10px #ef444488; }

.x-small { font-size: 0.75rem; }
</style>
@endsection
