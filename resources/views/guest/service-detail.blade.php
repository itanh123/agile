@extends('layouts.app')

@section('title', $service->name . ' - Pet Care Center')

@push('styles')
<style>
    .service-hero {
        background: linear-gradient(135deg, var(--primary) 0%, #8b5cf6 100%);
        color: white;
        padding: 3rem 0;
    }
    .price-card {
        border: 2px solid var(--primary);
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
{{-- Hero Section --}}
<div class="service-hero mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guest.services') }}" class="text-white-50">Dịch vụ</a></li>
                        <li class="breadcrumb-item text-white">{{ $service->name }}</li>
                    </ol>
                </nav>
                <span class="badge bg-light text-dark mb-2">{{ ucfirst($service->service_type) }}</span>
                <h1 class="display-4 fw-bold mb-3">{{ $service->name }}</h1>
                <div class="d-flex gap-4">
                    <div>
                        <i class="bi bi-clock"></i> {{ $service->duration_minutes }} phút
                    </div>
                    <div>
                        @switch($service->service_type)
                            @case('grooming') 🐕 @break
                            @case('vaccination') 💉 @break
                            @case('spa') ✨ @break
                            @case('checkup') 🩺 @break
                            @case('surgery') 🏥 @break
                            @default 🐾 @break
                        @endswitch
                        {{ ucfirst($service->service_type) }}
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="price-card bg-white text-dark p-4 d-inline-block">
                    <div class="text-muted small">Giá dịch vụ</div>
                    <div class="display-6 fw-bold text-primary">{{ number_format($service->price, 0, ',', '.') }}</div>
                    <div class="text-muted small">VNĐ</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            {{-- Mô tả --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title"><i class="bi bi-info-circle text-primary"></i> Mô tả dịch vụ</h4>
                    <p class="card-text">{!! nl2br(e($service->description ?: 'Dịch vụ chăm sóc thú cưng chất lượng cao, được thực hiện bởi đội ngũ chuyên nghiệp.')) !!}</p>
                </div>
            </div>

            {{-- Quy trình --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title"><i class="bi bi-list-check text-primary"></i> Quy trình dịch vụ</h4>
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fs-4 fw-bold text-primary">1</span>
                            </div>
                            <h6 class="mt-2">Tiếp nhận</h6>
                            <small class="text-muted">Check-in thú cưng</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fs-4 fw-bold text-primary">2</span>
                            </div>
                            <h6 class="mt-2">Thực hiện</h6>
                            <small class="text-muted">{{ $service->duration_minutes }} phút</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fs-4 fw-bold text-success">3</span>
                            </div>
                            <h6 class="mt-2">Hoàn thành</h6>
                            <small class="text-muted">Giao pet cho chủ</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lưu ý --}}
            <div class="card bg-light">
                <div class="card-body">
                    <h4 class="card-title"><i class="bi bi-exclamation-triangle text-warning"></i> Lưu ý</h4>
                    <ul class="mb-0">
                        <li>Vui lòng đến đúng giờ hẹn</li>
                        <li>Mang theo sổ tiêm phòng (nếu có)</li>
                        <li>Thông báo trước nếu cần hủy lịch</li>
                        <li>Thú cưng cần được rọ mõm (nếu có tính cách hung dữ)</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Đặt dịch vụ</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted">Giá:</span>
                        <span class="fw-bold text-primary fs-5">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Thời gian:</span>
                        <span>{{ $service->duration_minutes }} phút</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Loại dịch vụ:</span>
                        <span class="badge bg-secondary">{{ ucfirst($service->service_type) }}</span>
                    </div>

                    @auth
                        <a href="{{ route('customer.bookings.create') }}?service={{ $service->id }}" class="btn btn-primary w-100 btn-lg mb-2">
                            <i class="bi bi-calendar-plus"></i> Đặt lịch ngay
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg mb-2">
                            <i class="bi bi-box-arrow-in-right"></i> Đăng nhập để đặt
                        </a>
                        <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#quickBookModal">
                            <i class="bi bi-lightning"></i> Đặt nhanh không cần tài khoản
                        </button>
                    @endauth

                    <hr>

                    <div class="d-flex justify-content-between small text-muted">
                        <span><i class="bi bi-telephone"></i> 0901 234 567</span>
                        <span><i class="bi bi-clock"></i> 8:00 - 19:00</span>
                    </div>
                </div>
            </div>

            {{-- Quick Book Modal for Guest --}}
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
                            <input type="hidden" name="service_id" value="{{ $service->id }}">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="tel" name="phone" class="form-control" required placeholder="0xxx xxx xxx">
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
        </div>
    </div>

    {{-- Other Services --}}
    <div class="mt-5">
        <h3 class="mb-4">Dịch vụ khác</h3>
        <div class="row">
            @foreach(\App\Models\Service::where('is_active', true)->where('id', '!=', $service->id)->take(3)->get() as $other)
                <div class="col-md-4">
                    <a href="{{ route('guest.service', $other) }}" class="text-decoration-none">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="fs-1 mb-2">
                                    @switch($other->service_type)
                                        @case('grooming') 🐕 @break
                                        @case('vaccination') 💉 @break
                                        @case('spa') ✨ @break
                                        @case('checkup') 🩺 @break
                                        @case('surgery') 🏥 @break
                                        @default 🐾 @break
                                    @endswitch
                                </div>
                                <h6 class="text-dark">{{ $other->name }}</h6>
                                <span class="text-primary fw-bold">{{ number_format($other->price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection