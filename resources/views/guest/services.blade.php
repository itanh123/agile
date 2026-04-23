@extends('layouts.app')

@section('title', 'Dịch vụ - Pet Care Center')

@push('styles')
<style>
    .service-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .service-icon {
        font-size: 3rem;
    }
    .type-badge {
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3">Dịch vụ của chúng tôi</h1>
        <p class="text-muted lead">Khám phá các dịch vụ chăm sóc thú cưng chất lượng cao</p>
    </div>

    {{-- Filter --}}
    <div class="row mb-4">
        <div class="col-md-6 offset-md-3">
            <form method="GET" action="{{ route('guest.services') }}" class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Tìm kiếm dịch vụ..." value="{{ request('q') }}">
                <select name="type" class="form-select">
                    <option value="">Tất cả loại</option>
                    <option value="grooming" {{ request('type') === 'grooming' ? 'selected' : '' }}>Cắt tỉa lông</option>
                    <option value="vaccination" {{ request('type') === 'vaccination' ? 'selected' : '' }}>Tiêm phòng</option>
                    <option value="spa" {{ request('type') === 'spa' ? 'selected' : '' }}>Spa</option>
                    <option value="checkup" {{ request('type') === 'checkup' ? 'selected' : '' }}>Khám sức khỏe</option>
                    <option value="surgery" {{ request('type') === 'surgery' ? 'selected' : '' }}>Phẫu thuật</option>
                </select>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>

    @if($services->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted"></i>
            <p class="text-muted mt-3">Không tìm thấy dịch vụ nào</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-lg-4 col-md-6">
                    <div class="card service-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                @switch($service->service_type)
                                    @case('grooming') 🐕 @break
                                    @case('vaccination') 💉 @break
                                    @case('spa') ✨ @break
                                    @case('checkup') 🩺 @break
                                    @case('surgery') 🏥 @break
                                    @default 🐾 @break
                                @endswitch
                            </div>
                            <span class="badge bg-secondary type-badge mb-2">{{ ucfirst($service->service_type) }}</span>
                            <h5 class="card-title fw-bold">{{ $service->name }}</h5>
                            <p class="card-text text-muted small">{{ Str::limit($service->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-primary fw-bold fs-5">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                                <span class="text-muted small"><i class="bi bi-clock"></i> {{ $service->duration_minutes }} phút</span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('guest.service', $service) }}" class="btn btn-outline-primary">Xem chi tiết</a>
                                @auth
                                    <a href="{{ route('customer.bookings.create') }}?service={{ $service->id }}" class="btn btn-primary">Đặt ngay</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập để đặt</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $services->links() }}
        </div>
    @endif

    {{-- Quick Register Section --}}
    @guest
    <div class="card mt-5 bg-primary text-white">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-2">Đặt dịch vụ ngay với số điện thoại</h4>
                    <p class="mb-0 opacity-75">Không cần đăng ký! Chỉ cần số điện thoại là bạn có thể đặt lịch ngay.</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#quickBookModal">
                        <i class="bi bi-calendar-check"></i> Đặt nhanh
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endguest
</div>

{{-- Quick Book Modal --}}
@guest
<div class="modal fade" id="quickBookModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đặt lịch nhanh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('guest.quick-booking') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" name="phone" class="form-control" required placeholder="0xxx xxx xxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dịch vụ *</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">Chọn dịch vụ</option>
                            @foreach(\App\Models\Service::where('is_active', true)->get() as $svc)
                                <option value="{{ $svc->id }}">{{ $svc->name }} - {{ number_format($svc->price, 0, ',', '.') }}đ</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên thú cưng *</label>
                        <input type="text" name="pet_name" class="form-control" required placeholder="VD: Buddy">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày giờ hẹn *</label>
                        <input type="datetime-local" name="appointment_at" class="form-control" required min="{{ now()->addHour()->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Yêu cầu đặc biệt..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận đặt lịch</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endguest
@endsection