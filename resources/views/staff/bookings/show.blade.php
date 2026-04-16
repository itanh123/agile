@extends('staff.layout')

@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('staff.bookings.index') }}">Bookings</a>
        <span>/</span>
        <span>{{ $booking->booking_code }}</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <span class="text-muted">Booking</span> 
                <span class="text-primary">{{ $booking->booking_code }}</span>
            </h3>
            <p class="text-muted mb-0">Chi tiết công việc và các thao tác cần thiết.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.bookings.index') }}">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        <!-- Left Column - Booking Info -->
        <div class="col-lg-8">
            <!-- Booking Information -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2 text-primary"></i>Thông Tin Booking
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label class="text-muted small d-block">Trạng Thái</label>
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
                            <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }} fs-6">
                                {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label class="text-muted small d-block">Thanh Toán</label>
                            @php
                                $paymentColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'refunded' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $paymentColors[$booking->payment_status] ?? 'secondary' }} fs-6">
                                {{ ucfirst($booking->payment_status ?? 'pending') }}
                            </span>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label class="text-muted small d-block">Ngày Hẹn</label>
                            <div class="fw-medium">
                                @if($booking->appointment_at)
                                    {{ $booking->appointment_at->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3 mb-3">
                            <label class="text-muted small d-block">Giờ Hẹn</label>
                            <div class="fw-medium">
                                @if($booking->appointment_at)
                                    {{ $booking->appointment_at->format('H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small d-block">Hình Thức</label>
                            <div>{{ ucfirst($booking->service_mode ?? '-') }}</div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="text-muted small d-block">Tổng Tiền</label>
                            <div class="fw-bold text-success">{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} đ</div>
                        </div>
                        @if($booking->note)
                        <div class="col-12 mb-3">
                            <label class="text-muted small d-block">Ghi Chú Khách Hàng</label>
                            <div class="bg-light p-3 rounded">{{ $booking->note }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer & Pet Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-2 text-primary"></i>Khách Hàng
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="customer-avatar-lg">
                                    {{ strtoupper(substr($booking->user->fullname ?? $booking->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $booking->user->fullname ?? $booking->user->name }}</h6>
                                    <p class="text-muted mb-0 small">{{ $booking->user->email ?? '' }}</p>
                                </div>
                            </div>
                            @if($booking->user->phone)
                            <div class="mb-2">
                                <i class="bi bi-telephone me-2 text-muted"></i>
                                {{ $booking->user->phone }}
                            </div>
                            @endif
                            @if($booking->user->address)
                            <div>
                                <i class="bi bi-house me-2 text-muted"></i>
                                {{ $booking->user->address }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="bi bi-github me-2 text-primary"></i>Thông Tin Pet
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($booking->pet)
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if($booking->pet->avatar)
                                        <img src="{{ asset('storage/' . $booking->pet->avatar) }}" 
                                             alt="{{ $booking->pet->name }}" 
                                             class="rounded-circle" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="pet-avatar-lg">
                                            {{ strtoupper(substr($booking->pet->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{ $booking->pet->name }}</h6>
                                        <p class="text-muted mb-0 small">
                                            {{ $booking->pet->category?->name ?? '' }} - 
                                            {{ $booking->pet->breed?->name ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row g-2 small">
                                    @if($booking->pet->gender)
                                    <div class="col-6">
                                        <i class="bi bi-heart me-1 text-muted"></i>
                                        {{ ucfirst($booking->pet->gender) }}
                                    </div>
                                    @endif
                                    @if($booking->pet->weight)
                                    <div class="col-6">
                                        <i class="bi bi-box me-1 text-muted"></i>
                                        {{ $booking->pet->weight }} kg
                                    </div>
                                    @endif
                                    @if($booking->pet->color)
                                    <div class="col-6">
                                        <i class="bi bi-palette me-1 text-muted"></i>
                                        {{ $booking->pet->color }}
                                    </div>
                                    @endif
                                    @if($booking->pet->health_status)
                                    <div class="col-6">
                                        <i class="bi bi-activity me-1 text-muted"></i>
                                        {{ ucfirst($booking->pet->health_status) }}
                                    </div>
                                    @endif
                                </div>
                                @if($booking->pet->allergies)
                                <div class="alert alert-warning py-2 mt-2 mb-0 small">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    <strong>Dị ứng:</strong> {{ $booking->pet->allergies }}
                                </div>
                                @endif
                            @else
                                <p class="text-muted mb-0">Không có thông tin pet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2 text-primary"></i>Dịch Vụ Đã Chọn
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($booking->services->isEmpty())
                        <div class="p-4 text-center text-muted">Chưa có dịch vụ nào được chọn.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Dịch Vụ</th>
                                        <th class="text-center">Số Lượng</th>
                                        <th class="text-end">Đơn Giá</th>
                                        <th class="text-end">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->services as $service)
                                        <tr>
                                            <td>
                                                <span class="fw-medium">{{ $service->name }}</span>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                            </td>
                                            <td class="text-center">{{ $service->pivot->quantity ?? 1 }}</td>
                                            <td class="text-end">{{ number_format($service->pivot->unit_price ?? $service->price, 0, ',', '.') }} đ</td>
                                            <td class="text-end fw-medium">{{ number_format($service->pivot->line_total ?? $service->price, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                        <td class="text-end fw-bold text-success">{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} đ</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-images me-2 text-primary"></i>Hình Ảnh Tiến Độ
                        <span class="badge bg-primary ms-2">{{ $booking->images->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->images->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-image" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-0">Chưa có hình ảnh tiến độ nào.</p>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($booking->images as $image)
                                <div class="col-md-4 col-lg-3">
                                    <div class="image-card position-relative">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="Progress image" 
                                             class="img-fluid rounded"
                                             style="height: 150px; object-fit: cover; width: 100%;">
                                        <div class="image-overlay">
                                            <button type="button" 
                                                    class="btn btn-sm btn-light" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#imageModal{{ $image->id }}">
                                                <i class="bi bi-zoom-in"></i>
                                            </button>
                                        </div>
                                        @if($image->caption)
                                        <div class="image-caption small text-muted px-2 py-1">
                                            {{ Str::limit($image->caption, 30) }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    @if($image->caption)
                                                        {{ $image->caption }}
                                                    @else
                                                        Hình Ảnh Tiến Độ
                                                    @endif
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="Progress image" 
                                                     class="img-fluid">
                                                <div class="mt-3 text-start">
                                                    <p class="mb-1">
                                                        <i class="bi bi-calendar me-2"></i>
                                                        {{ $image->taken_at ? $image->taken_at->format('d/m/Y H:i') : '-' }}
                                                    </p>
                                                    @if($image->uploader)
                                                    <p class="mb-0">
                                                        <i class="bi bi-person me-2"></i>
                                                        Đăng bởi: {{ $image->uploader->fullname ?? $image->uploader->name }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status Log History -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Lịch Sử Hoạt Động
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($booking->logs->isEmpty())
                        <div class="p-4 text-center text-muted">Chưa có lịch sử nào.</div>
                    @else
                        <div class="timeline p-4">
                            @foreach($booking->logs->sortByDesc('changed_at') as $log)
                                <div class="timeline-item mb-4">
                                    <div class="d-flex gap-3">
                                        <div class="timeline-marker">
                                            @php
                                                $logColors = [
                                                    'pending' => 'secondary',
                                                    'confirmed' => 'info',
                                                    'processing' => 'warning',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $logColors[$log->status] ?? 'secondary' }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </div>
                                        <div class="timeline-content flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="fw-medium">{{ $log->note }}</span>
                                                    <br>
                                                    <small class="text-muted">
                                                        @if($log->changedByUser)
                                                            {{ $log->changedByUser->fullname ?? $log->changedByUser->name }}
                                                        @else
                                                            Hệ thống
                                                        @endif
                                                    </small>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $log->changed_at ? $log->changed_at->format('d/m/Y H:i') : '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions -->
        <div class="col-lg-4">
            <!-- Update Status Form -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-arrow-repeat me-2 text-primary"></i>Cập Nhật Trạng Thái
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->status === 'completed' || $booking->status === 'cancelled')
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Booking này đã {{ $booking->status === 'completed' ? 'hoàn thành' : 'bị hủy' }}.
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('staff.bookings.update-status', $booking) }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Trạng thái mới</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="">-- Chọn trạng thái --</option>
                                @foreach(['confirmed', 'processing', 'completed', 'cancelled'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status) @selected(old('status') === $status)>
                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lý do / Ghi chú</label>
                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                      rows="3" 
                                      placeholder="Nhập lý do thay đổi trạng thái...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100" 
                                @if($booking->status === 'completed' || $booking->status === 'cancelled') disabled @endif>
                            <i class="bi bi-check-lg me-2"></i>Cập Nhật Trạng Thái
                        </button>
                    </form>
                </div>
            </div>

            <!-- Upload Image Form -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-camera me-2 text-primary"></i>Tải Lên Hình Ảnh
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.bookings.upload-image', $booking) }}" 
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Chọn hình ảnh (tối đa 5MB)</label>
                            <input type="file" name="images[]" id="imageInput" 
                                   class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                   accept="image/*" multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagePreview" class="mt-2 row g-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <input type="text" name="caption" class="form-control" 
                                   placeholder="Mô tả ngắn về hình ảnh..." 
                                   value="{{ old('caption') }}">
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="uploadBtn" disabled>
                            <i class="bi bi-cloud-upload me-2"></i>Tải Lên
                        </button>
                    </form>
                </div>
            </div>

            <!-- Add Note Form -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-sticky me-2 text-primary"></i>Thêm Ghi Chú
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('staff.bookings.add-note', $booking) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Loại ghi chú</label>
                            <select name="note_type" class="form-select">
                                <option value="general" @selected(old('note_type') === 'general')>Ghi chú chung</option>
                                <option value="condition" @selected(old('note_type') === 'condition')>Tình trạng pet</option>
                                <option value="mood" @selected(old('note_type') === 'mood')>Tâm trạng</option>
                                <option value="health" @selected(old('note_type') === 'health')>Sức khỏe</option>
                                <option value="warning" @selected(old('note_type') === 'warning')>Cảnh báo</option>
                                <option value="progress" @selected(old('note_type') === 'progress')>Tiến độ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung ghi chú</label>
                            <textarea name="note" class="form-control @error('note') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Nhập ghi chú về tình trạng, tiến độ công việc...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-plus-lg me-2"></i>Thêm Ghi Chú
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .customer-avatar-lg {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #3498db;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }
    
    .pet-avatar-lg {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #2ecc71;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }
    
    .image-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }
    
    .image-card:hover .image-overlay {
        opacity: 1;
    }
    
    .image-caption {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-left: 20px;
        border-left: 2px solid #e9ecef;
    }
    
    .timeline-item:last-child {
        border-left: none;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0 !important;
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        left: -11px;
        background: #fff;
    }
    
    .preview-image {
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const uploadBtn = document.getElementById('uploadBtn');
        preview.innerHTML = '';
        
        if (this.files.length > 0) {
            uploadBtn.disabled = false;
            
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-4';
                    col.innerHTML = `<img src="${e.target.result}" class="preview-image w-100">`;
                    preview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
        } else {
            uploadBtn.disabled = true;
        }
    });
</script>
@endpush
