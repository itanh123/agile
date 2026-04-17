@extends('layouts.app')
@section('title', 'Đặt lịch chăm sóc')

@section('content')
<div class="row justify-content-center py-4 animate-fade-up">
    <div class="col-lg-10">
        <div class="glass-card p-4 p-md-5 mt-4">
            <div class="d-flex align-items-center gap-3 mb-5">
                <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-calendar-plus fs-3"></i>
                </div>
                <div>
                    <h2 class="text-white fw-bold mb-1">Đặt Lịch Hẹn Mới</h2>
                    <p class="text-muted mb-0">Dành cho thú cưng của bạn những dịch vụ chăm sóc tốt nhất</p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.bookings.store') }}" id="bookingForm">
                @csrf
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-0">Chọn Thú Cưng <span class="text-danger">*</span></label>
                            <a href="{{ route('customer.pets.create') }}" class="small text-primary text-decoration-none">
                                <i class="bi bi-plus-circle me-1"></i>Thêm thú cưng mới
                            </a>
                        </div>
                        
                        @if($pets->isEmpty())
                            <div class="glass p-4 text-center border-dashed" style="border: 2px dashed rgba(255,255,255,0.1); background: rgba(255,255,255,0.02);">
                                <div class="fs-1 mb-2 text-muted opacity-50">🐾</div>
                                <p class="text-muted small mb-3">Bạn chưa đăng ký thú cưng nào trong hệ thống.</p>
                                <a href="{{ route('customer.pets.create') }}" class="btn-premium btn-sm py-1 px-4">Đăng ký ngay</a>
                            </div>
                        @else
                            <div class="row g-3">
                                @foreach($pets as $pet)
                                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                        <label class="pet-select-card h-100 w-100 cursor-pointer">
                                            <input type="radio" name="pet_id" value="{{ $pet->id }}" class="d-none" @checked(old('pet_id') == $pet->id || ($loop->first && !old('pet_id')))>
                                            <div class="glass-card p-3 text-center transition-all h-100 d-flex flex-column align-items-center justify-content-center border-opacity-10 shadow-sm">
                                                <div class="pet-avatar-wrapper mb-2 position-relative">
                                                    <div class="p-2 bg-light bg-opacity-10 rounded-circle text-center d-flex align-items-center justify-content-center transition-all avatar-bg" style="width: 50px; height: 50px;">
                                                        <span class="fs-4">{{ $pet->category?->slug === 'dog' ? '🐶' : ($pet->category?->slug === 'cat' ? '🐱' : '🐾') }}</span>
                                                    </div>
                                                    <div class="check-icon position-absolute top-0 end-0 bg-primary rounded-circle scale-0 transition-all shadow" style="width: 18px; height: 18px; font-size: 10px; line-height: 18px; color: white;">
                                                        <i class="bi bi-check-lg"></i>
                                                    </div>
                                                </div>
                                                <div class="text-white fw-bold small text-truncate w-100">{{ $pet->name }}</div>
                                                <div class="text-muted x-small text-truncate w-100">{{ $pet->breed ?? 'Giống lai' }}</div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @error('pet_id') <div class="text-danger mt-2 small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ngày & Giờ Hẹn</label>
                        <input type="datetime-local" name="appointment_at" class="form-control-premium w-100" 
                               value="{{ old('appointment_at') }}" required>
                        @error('appointment_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Hình thức dịch vụ</label>
                        <select name="service_mode" class="form-select-premium w-100">
                            <option value="at_store" @selected(old('service_mode') == 'at_store')>Tại cửa hàng</option>
                            <option value="at_home" @selected(old('service_mode') == 'at_home')>Tại nhà</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Phương thức thanh toán</label>
                        <select name="payment_method" class="form-select-premium w-100">
                            <option value="cash" @selected(old('payment_method') == 'cash')>Tiền mặt</option>
                            <option value="vnpay" @selected(old('payment_method') == 'vnpay')>VNPAY</option>
                            <option value="momo" @selected(old('payment_method') == 'momo')>MoMo</option>
                            <option value="transfer" @selected(old('payment_method') == 'transfer')>Chuyển khoản</option>
                        </select>
                    </div>

                    <div class="col-md-8">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label text-muted small fw-bold text-uppercase">Mã Giảm Giá</label>
                            <a href="#" class="small text-primary text-decoration-none mb-2" id="btn-show-vouchers">
                                <i class="bi bi-gift me-1"></i>Xem ưu đãi hiện có
                            </a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 text-muted" style="border-color: var(--glass-border); border-radius: 0.75rem 0 0 0.75rem;">
                                <i class="bi bi-tag"></i>
                            </span>
                            <input name="promotion_code" id="promotion_code" class="form-control-premium flex-grow-1" 
                                   value="{{ old('promotion_code') }}" placeholder="Nhập mã (nếu có)" style="border-radius: 0;">
                            <button type="button" id="btn-apply-coupon" class="btn-premium" style="border-top-left-radius: 0; border-bottom-left-radius: 0; padding: 0.5rem 1.5rem;">Áp dụng</button>
                        </div>
                        <div id="coupon-message" class="small mt-2"></div>
                    </div>

                    <div class="col-12 mt-5">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-4 d-block text-center">
                            <span class="px-3" style="background: var(--bg-dark); position: relative; z-index: 1;">Chọn Dịch Vụ</span>
                            <hr style="margin-top: -12px; opacity: 0.1;">
                        </label>
                        <div class="row g-3">
                            @foreach($services as $service)
                                <div class="col-md-6 col-xl-4">
                                    <label class="service-option-card d-flex align-items-center p-3 glass-card h-100 cursor-pointer animate-fade-up">
                                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" 
                                               data-price="{{ $service->price }}" 
                                               @checked(is_array(old('service_ids')) && in_array($service->id, old('service_ids')))
                                               class="form-check-input checkbox-premium service-checkbox me-3">
                                        <div class="flex-grow-1">
                                            <div class="text-white fw-bold">{{ $service->name }}</div>
                                            <div class="text-primary small">{{ number_format($service->price, 0) }} VNĐ</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('service_ids') <div class="text-danger mt-2 text-center small">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Ghi chú thêm</label>
                        <textarea name="note" class="form-control-premium w-100" rows="3" placeholder="Yêu cầu đặc biệt cho thú cưng của bạn...">{{ old('note') }}</textarea>
                    </div>

                    <!-- Price Summary -->
                    <div class="col-12 mt-4">
                        <div class="glass p-4 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tạm tính</span>
                                <span class="text-white fw-bold" id="display-subtotal">0 VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-primary">
                                <span>Giảm giá</span>
                                <span class="fw-bold" id="display-discount">- 0 VNĐ</span>
                            </div>
                            <hr class="opacity-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-white fw-bold fs-5">Tổng cộng</span>
                                <span class="text-primary fs-3 fw-bold" id="display-total">0 VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('customer.bookings.index') }}" class="btn-outline-premium">Hủy bỏ</a>
                    <button type="submit" class="btn-premium px-5 py-2">Xác nhận đặt lịch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Vouchers Modal -->
<div class="modal fade" id="vouchersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0" style="background: rgba(23, 23, 35, 0.95); backdrop-filter: blur(20px);">
            <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                <h5 class="modal-title text-white fw-bold">Ưu Đãi Có Sẵn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="vouchers-list">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.service-checkbox');
    const displaySubtotal = document.getElementById('display-subtotal');
    const displayDiscount = document.getElementById('display-discount');
    const displayTotal = document.getElementById('display-total');
    const btnApply = document.getElementById('btn-apply-coupon');
    const inputCoupon = document.getElementById('promotion_code');
    const couponMessage = document.getElementById('coupon-message');
    const btnShowVouchers = document.getElementById('btn-show-vouchers');
    const vouchersList = document.getElementById('vouchers-list');
    const vouchersModal = new bootstrap.Modal(document.getElementById('vouchersModal'));

    let currentSubtotal = 0;
    let currentDiscount = 0;

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
    }

    function calculateTotals() {
        currentSubtotal = 0;
        checkboxes.forEach(cb => {
            if (cb.checked) {
                currentSubtotal += parseFloat(cb.getAttribute('data-price'));
            }
        });
        
        // If discount exists, we should probably re-check it
        if (inputCoupon.value.trim() && currentDiscount > 0) {
            applyCoupon(); // Re-apply to update percentage discount
        } else {
            updateDisplay();
        }
    }

    function updateDisplay() {
        displaySubtotal.textContent = formatPrice(currentSubtotal);
        displayDiscount.textContent = '- ' + formatPrice(currentDiscount);
        displayTotal.textContent = formatPrice(Math.max(currentSubtotal - currentDiscount, 0));
    }

    function applyCoupon(code = null) {
        const finalCode = code || inputCoupon.value.trim();
        if (!finalCode) {
            currentDiscount = 0;
            couponMessage.textContent = '';
            updateDisplay();
            return;
        }

        if (code) inputCoupon.value = code;

        btnApply.disabled = true;
        const originalText = btnApply.textContent;
        btnApply.textContent = '...';

        fetch(`{{ route('customer.promotions.check') }}?code=${finalCode}&subtotal=${currentSubtotal}`)
            .then(response => response.json())
            .then(data => {
                btnApply.disabled = false;
                btnApply.textContent = originalText;
                
                if (data.success) {
                    currentDiscount = data.discount;
                    couponMessage.textContent = data.message;
                    couponMessage.className = 'small mt-2 text-primary fw-bold';
                    updateDisplay();
                } else {
                    currentDiscount = 0;
                    couponMessage.textContent = data.message;
                    couponMessage.className = 'small mt-2 text-danger';
                    updateDisplay();
                }
            })
            .catch(error => {
                btnApply.disabled = false;
                btnApply.textContent = originalText;
                console.error('Error:', error);
            });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', calculateTotals);
    });

    btnApply.addEventListener('click', () => applyCoupon());

    btnShowVouchers.addEventListener('click', function(e) {
        e.preventDefault();
        vouchersModal.show();
        
        fetch(`{{ route('customer.promotions.available') }}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.promotions.length > 0) {
                    let html = '';
                    data.promotions.forEach(p => {
                        const discount = p.discount_type === 'percent' ? p.discount_value + '%' : formatPrice(p.discount_value);
                        html += `
                            <div class="voucher-card p-3 rounded-4 mb-3 border border-white border-opacity-10 transition-all hover-border-primary cursor-pointer" 
                                 onclick="selectVoucher('${p.code}')" style="cursor: pointer; transition: all 0.3s ease;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary bg-opacity-20 text-primary px-3 py-2" style="letter-spacing: 1px; font-weight: 700;">${p.code}</span>
                                    <span class="text-primary fw-bold fs-5">-${discount}</span>
                                </div>
                                <h6 class="text-white mb-1">${p.title}</h6>
                                <p class="text-muted small mb-0">${p.description || 'Không có mô tả'}</p>
                                <div class="mt-2 text-muted x-small" style="font-size: 0.7rem;">
                                    <i class="bi bi-info-circle me-1"></i>Đơn tối thiểu: ${formatPrice(p.min_order_amount)}
                                </div>
                            </div>
                        `;
                    });
                    vouchersList.innerHTML = html;
                } else {
                    vouchersList.innerHTML = '<p class="text-center text-muted py-4">Hiện không có ưu đãi khả dụng nào.</p>';
                }
            });
    });

    window.selectVoucher = function(code) {
        vouchersModal.hide();
        applyCoupon(code);
    };

    calculateTotals();
});
</script>
@endpush

<style>
    .service-option-card {
        border: 1px solid var(--glass-border);
        transition: var(--transition);
    }
    .service-option-card:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--primary);
        transform: translateY(-2px);
    }
    .service-option-card:has(input:checked) {
        background: rgba(99, 102, 241, 0.15);
        border-color: var(--primary);
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
    }
    .pet-select-card .glass-card {
        border: 1px solid rgba(255,255,255,0.05);
    }
    .pet-select-card input:checked + .glass-card {
        background: rgba(99, 102, 241, 0.2);
        border-color: var(--primary);
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
    }
    .pet-select-card input:checked + .glass-card .avatar-bg {
        background: rgba(99, 102, 241, 0.2) !important;
        transform: scale(1.1);
    }
    .pet-select-card input:checked + .glass-card .check-icon {
        transform: scale(1);
    }
    .scale-0 { transform: scale(0); }
    .voucher-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: var(--primary) !important;
        transform: scale(1.02);
    }
    .hover-border-primary:hover {
        border-color: var(--primary) !important;
    }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
