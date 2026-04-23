@extends('layouts.app')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-calendar-plus"></i> Đặt lịch chăm sóc</h2>

        <form method="POST" action="{{ route('customer.bookings.store') }}" class="card card-body">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Chọn thú cưng *</label>
                    <select name="pet_id" class="form-select" required>
                        <option value="">-- Chọn thú cưng --</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->category->name ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                    @error('pet_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ngày giờ hẹn *</label>
                    <input type="datetime-local" name="appointment_at" class="form-control" required value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}">
                    @error('appointment_at') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Hình thức dịch vụ *</label>
                    <select name="service_mode" class="form-select" required>
                        <option value="at_store">Tại cửa hàng</option>
                        <option value="at_home">Tại nhà (có phí)</option>
                        <option value="pickup">Nhận thú qua nhân viên shop</option>
                    </select>
                    @error('service_mode') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phương thức thanh toán *</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash">Tiền mặt (tại quầy)</option>
                        <option value="vnpay">Thanh toán online qua VNPay</option>
                    </select>
                    @error('payment_method') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Mã giảm giá (nếu có)</label>
                    <input type="text" name="promotion_code" class="form-control" placeholder="Nhập mã voucher">
                    @error('promotion_code') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Chọn dịch vụ *</label>
                    <div class="row">
                        @foreach($services as $service)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="service_ids[]" value="{{ $service->id }}" id="service_{{ $service->id }}">
                                    <label class="form-check-label" for="service_{{ $service->id }}">
                                        {{ $service->name }}
                                        <span class="text-primary fw-bold">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                                        <small class="text-muted">({{ $service->duration_minutes }} phút)</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('service_ids') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Yêu cầu đặc biệt, lưu ý cho thú cưng..."></textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Xác nhận đặt lịch
                </button>
                <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
            </div>
        </form>
    </div>
@endsection