@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <span class="text-muted">Booking</span> 
                <span class="text-primary">{{ $booking->booking_code }}</span>
            </h3>
            <p class="text-muted mb-0">Theo dõi tiến độ dịch vụ cho thú cưng của bạn.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('customer.dashboard') }}">
            <i class="bi bi-arrow-left me-2"></i>Về Dashboard
        </a>
    </div>

    <!-- Status Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h6 class="text-muted mb-2">Trạng Thái Hiện Tại</h6>
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
                        $statusIcons = [
                            'pending' => 'bi-hourglass-split',
                            'confirmed' => 'bi-check-circle',
                            'processing' => 'bi-gear',
                            'completed' => 'bi-check-circle-fill',
                            'cancelled' => 'bi-x-circle'
                        ];
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }} fs-6 py-2 px-3">
                            <i class="bi {{ $statusIcons[$booking->status] ?? 'bi-circle' }} me-1"></i>
                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-center border-start border-end">
                    <h6 class="text-muted mb-2">Ngày Hẹn</h6>
                    <p class="mb-0 fs-5 fw-medium">
                        @if($booking->appointment_at)
                            {{ $booking->appointment_at->format('d/m/Y') }}
                            <small class="text-muted ms-2">{{ $booking->appointment_at->format('H:i') }}</small>
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <h6 class="text-muted mb-2">Tổng Thanh Toán</h6>
                    <p class="mb-0 fs-5 fw-bold text-success">{{ number_format($booking->total_amount ?? 0, 0, ',', '.') }} đ</p>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-4">
                @php
                    $statusOrder = ['pending', 'confirmed', 'processing', 'completed'];
                    $currentIndex = array_search($booking->status, $statusOrder);
                    if ($booking->status === 'cancelled') {
                        $currentIndex = -1;
                    }
                @endphp
                <div class="d-flex justify-content-between position-relative mb-2">
                    @foreach($statusOrder as $index => $status)
                        <div class="text-center flex-fill">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center 
                                        {{ $currentIndex >= $index && $booking->status !== 'cancelled' ? 'bg-success text-white' : 'bg-light text-muted' }}"
                                 style="width: 35px; height: 35px;">
                                <i class="bi {{ $statusIcons[$status] }}"></i>
                            </div>
                            <br>
                            <small class="{{ $currentIndex >= $index && $booking->status !== 'cancelled' ? 'text-success fw-medium' : 'text-muted' }}">
                                {{ $statusLabels[$status] }}
                            </small>
                        </div>
                    @endforeach
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar {{ $booking->status === 'cancelled' ? 'bg-danger' : 'bg-success' }}" 
                         role="progressbar" 
                         style="width: {{ $booking->status === 'cancelled' ? '100%' : (($currentIndex + 1) / count($statusOrder) * 100) . '%' }}"
                         aria-valuenow="{{ $currentIndex + 1 }}" 
                         aria-valuemin="0" 
                         aria-valuemax="{{ count($statusOrder) }}"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Services -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2 text-primary"></i>Dịch Vụ Đã Đặt
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
                                        <th class="text-end">Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->services as $service)
                                        <tr>
                                            <td>
                                                <span class="fw-medium">{{ $service->name }}</span>
                                            </td>
                                            <td class="text-center">{{ $service->pivot->quantity ?? 1 }}</td>
                                            <td class="text-end">{{ number_format($service->pivot->line_total ?? $service->price, 0, ',', '.') }} đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Images -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-images me-2 text-primary"></i>Hình Ảnh Tiến Độ
                        <span class="badge bg-primary ms-2">{{ $booking->images->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($booking->images->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-camera" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-0">Nhân viên chưa đăng hình ảnh tiến độ nào.</p>
                            <small>Chúng tôi sẽ cập nhật hình ảnh trong quá trình chăm sóc thú cưng.</small>
                        </div>
                    @else
                        <div class="row g-3">
                            @foreach($booking->images as $image)
                                <div class="col-md-4 col-lg-3">
                                    <div class="image-card position-relative rounded overflow-hidden" 
                                         style="border: 1px solid #e9ecef; cursor: pointer;"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             alt="Progress image" 
                                             class="img-fluid"
                                             style="height: 150px; object-fit: cover; width: 100%;">
                                        <div class="image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;">
                                            <i class="bi bi-zoom-in text-white" style="font-size: 24px;"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hình Ảnh Tiến Độ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                     alt="Progress image" 
                                                     class="img-fluid">
                                                @if($image->caption)
                                                    <p class="mt-3 mb-2 text-start">
                                                        <i class="bi bi-card-text me-2"></i>
                                                        {{ $image->caption }}
                                                    </p>
                                                @endif
                                                <p class="text-muted mb-0 text-start">
                                                    <i class="bi bi-calendar me-2"></i>
                                                    {{ $image->taken_at ? $image->taken_at->format('d/m/Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Cập Nhật Mới Nhất
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($booking->logs->isEmpty())
                        <div class="p-4 text-center text-muted">Chưa có cập nhật nào.</div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($booking->logs->sortByDesc('changed_at')->take(5) as $log)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <i class="bi bi-{{ $log->status === 'completed' ? 'check-circle-fill text-success' : ($log->status === 'cancelled' ? 'x-circle text-danger' : 'arrow-right-circle text-info') }} me-2"></i>
                                            <span>{{ $log->note }}</span>
                                        </div>
                                        <small class="text-muted">
                                            {{ $log->changed_at ? $log->changed_at->diffForHumans() : '' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Pet Info -->
            @if($booking->pet)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-github me-2 text-primary"></i>Thông Tin Pet
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($booking->pet->avatar)
                            <img src="{{ asset('storage/' . $booking->pet->avatar) }}" 
                                 alt="{{ $booking->pet->name }}" 
                                 class="rounded-circle mb-2" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success text-white mb-2" 
                                 style="width: 80px; height: 80px; font-size: 32px;">
                                {{ strtoupper(substr($booking->pet->name, 0, 1)) }}
                            </div>
                        @endif
                        <h5 class="mb-1">{{ $booking->pet->name }}</h5>
                        <p class="text-muted mb-0 small">
                            {{ $booking->pet->category?->name ?? '' }} - 
                            {{ $booking->pet->breed?->name ?? '' }}
                        </p>
                    </div>
                    <hr>
                    <div class="small">
                        @if($booking->pet->gender)
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Giới tính:</div>
                            <div class="col-7">{{ ucfirst($booking->pet->gender) }}</div>
                        </div>
                        @endif
                        @if($booking->pet->weight)
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Cân nặng:</div>
                            <div class="col-7">{{ $booking->pet->weight }} kg</div>
                        </div>
                        @endif
                        @if($booking->pet->age())
                        <div class="row mb-2">
                            <div class="col-5 text-muted">Tuổi:</div>
                            <div class="col-7">{{ $booking->pet->age() }}</div>
                        </div>
                        @endif
                    </div>
                    @if($booking->pet->allergies)
                    <div class="alert alert-warning py-2 mb-0 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Dị ứng:</strong> {{ $booking->pet->allergies }}
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear me-2 text-primary"></i>Hành Động
                    </h5>
                </div>
                <div class="card-body">
                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('customer.bookings.reschedule', $booking) }}" class="mb-3">
                            @csrf
                            @method('PATCH')
                            <label class="form-label small text-muted">Đổi lịch hẹn</label>
                            <div class="input-group">
                                <input type="datetime-local" name="appointment_at" class="form-control" required>
                                <button class="btn btn-outline-warning">Đổi lịch</button>
                            </div>
                        </form>
                        
                        <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Bạn có chắc muốn hủy booking này?')">
                                <i class="bi bi-x-circle me-2"></i>Hủy Booking
                            </button>
                        </form>
                    @else
                        <div class="text-muted small">
                            @if($booking->status === 'processing')
                                <i class="bi bi-info-circle me-1"></i>
                                Booking đang được xử lý. Vui lòng chờ thông báo tiếp theo.
                            @elseif($booking->status === 'completed')
                                <i class="bi bi-check-circle me-1"></i>
                                Cảm ơn bạn đã sử dụng dịch vụ của PetCare!
                            @elseif($booking->status === 'cancelled')
                                <i class="bi bi-x-circle me-1"></i>
                                Booking này đã bị hủy.
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .image-card:hover .image-overlay {
        opacity: 1 !important;
    }
</style>
@endpush
