@extends('layouts.admin')
@section('title', 'Tạo Voucher Mới')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.promotions.index') }}">Vouchers</a>
<i class="bi bi-chevron-right"></i>
<span>Thêm mới</span>
@endpush

@section('content')
<div class="row" id="promotionApp">
    <div class="col-lg-8">
        <div class="admin-card">
            <h4 class="mb-4"><i class="bi bi-plus-circle me-2 text-primary"></i>Thông tin Voucher</h4>
            
            <form method="POST" action="{{ route('admin.promotions.store') }}" novalidate>
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mã Voucher <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="code" id="promoCode" class="form-control-admin" 
                                   value="{{ old('code') }}" 
                                   placeholder="VD: GIAM20K" required style="text-transform: uppercase; font-family: monospace; font-weight: bold; letter-spacing: 1px;">
                            <button type="button" class="btn-admin btn-admin-secondary" id="generateBtn" title="Tạo mã ngẫu nhiên">
                                <i class="bi bi-magic"></i>
                            </button>
                        </div>
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tiêu đề hiển thị <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="promoTitle" class="form-control-admin" 
                               value="{{ old('title') }}" 
                               placeholder="VD: Giảm 20% cho đơn hàng đầu tiên" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Loại giảm giá <span class="text-danger">*</span></label>
                        <select name="discount_type" id="discountType" class="form-select-admin" required>
                            <option value="percent" @selected(old('discount_type') == 'percent')>Theo Phần trăm (%)</option>
                            <option value="fixed" @selected(old('discount_type') == 'fixed')>Số tiền cố định (VNĐ)</option>
                        </select>
                        @error('discount_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Giá trị giảm <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="discount_value" id="discountValue" class="form-control-admin" 
                                   value="{{ old('discount_value') }}" 
                                   placeholder="e.g., 20" min="0" required>
                            <span class="input-group-text bg-light" id="discountSymbol">%</span>
                        </div>
                        @error('discount_value')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Đơn hàng tối thiểu (VNĐ)</label>
                        <input type="number" name="min_order_amount" id="minOrder" class="form-control-admin" 
                               value="{{ old('min_order_amount', 0) }}" 
                               placeholder="0 nếu không giới hạn" min="0">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Giới hạn số lượt dùng</label>
                        <input type="number" name="usage_limit" class="form-control-admin" 
                               value="{{ old('usage_limit') }}" 
                               placeholder="Để trống nếu không giới hạn" min="1">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_at" class="form-control-admin" 
                               value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('start_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="end_at" class="form-control-admin" 
                               value="{{ old('end_at') }}" required>
                        @error('end_at')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select name="is_active" class="form-select-admin">
                            <option value="1" @selected(old('is_active', 1) == 1)>Đang hoạt động</option>
                            <option value="0" @selected(old('is_active', 1) == 0)>Tạm dừng</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea name="description" id="promoDesc" class="form-control-admin" rows="3" 
                                  placeholder="Mô tả cho khách hàng biết về ưu đãi này...">{{ old('description') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-5 d-flex gap-2">
                    <button type="submit" class="btn-admin btn-admin-primary px-4 py-2">
                        <i class="bi bi-check-lg me-2"></i> Lưu Voucher
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn-admin btn-admin-secondary px-4 py-2">
                        <i class="bi bi-x-lg me-2"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="sticky-top" style="top: 20px;">
            <h5 class="mb-3 text-muted" style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Xem trước (Preview)</h5>
            
            <div id="voucherPreview" class="admin-card" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; overflow: hidden; position: relative;">
                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: -10px; width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div style="background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 30px; font-size: 0.75rem; font-weight: 700;">
                        PETCARE VOUCHER
                    </div>
                    <i class="bi bi-stars fs-4"></i>
                </div>
                
                <h4 id="previewTitle" class="mb-1" style="font-weight: 700;">Tiêu đề Voucher</h4>
                <p id="previewDesc" class="mb-4" style="font-size: 0.8rem; opacity: 0.9; min-height: 2.4rem;">Mô tả ưu đãi sẽ hiển thị ở đây...</p>
                
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <span id="previewValue" class="d-block lh-1" style="font-size: 2.5rem; font-weight: 800;">-20%</span>
                        <span id="previewMin" style="font-size: 0.7rem; opacity: 0.8;">Đơn tối thiểu: 0đ</span>
                    </div>
                    <div class="text-end">
                        <span class="d-block" style="font-size: 0.65rem; opacity: 0.8;">MÃ GIẢM GIÁ</span>
                        <span id="previewCode" style="font-size: 1.2rem; font-weight: 700; font-family: monospace; letter-spacing: 1px;">GIAM20K</span>
                    </div>
                </div>
            </div>

            <div class="admin-card mt-4">
                <h5 class="admin-card-title mb-3">Mẹo nhỏ</h5>
                <div class="detail-section">
                    <div class="mb-3">
                        <strong class="text-primary d-block mb-1">Giảm theo %</strong>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Phù hợp cho các chương trình khuyến mãi theo mùa. Ví dụ: Giảm 20% toàn bộ dịch vụ.</p>
                    </div>
                    <div>
                        <strong class="text-success d-block mb-1">Giảm số tiền cố định</strong>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Tốt cho khách hàng mới hoặc tri ân. Ví dụ: Tặng ngay 50.000đ cho đơn đầu tiên.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const promoCode = document.getElementById('promoCode');
    const promoTitle = document.getElementById('promoTitle');
    const promoDesc = document.getElementById('promoDesc');
    const discountType = document.getElementById('discountType');
    const discountValue = document.getElementById('discountValue');
    const discountSymbol = document.getElementById('discountSymbol');
    const minOrder = document.getElementById('minOrder');
    const generateBtn = document.getElementById('generateBtn');

    // Previews
    const previewCode = document.getElementById('previewCode');
    const previewTitle = document.getElementById('previewTitle');
    const previewDesc = document.getElementById('previewDesc');
    const previewValue = document.getElementById('previewValue');
    const previewMin = document.getElementById('previewMin');

    function updatePreview() {
        previewCode.textContent = promoCode.value || 'GIA-TRI';
        previewTitle.textContent = promoTitle.value || 'Tiêu đề Voucher';
        previewDesc.textContent = promoDesc.value || 'Mô tả ưu đãi sẽ hiển thị ở đây...';
        
        let val = discountValue.value || 0;
        if (discountType.value === 'percent') {
            previewValue.textContent = '-' + val + '%';
            discountSymbol.textContent = '%';
        } else {
            previewValue.textContent = '-' + parseInt(val).toLocaleString('vi-VN') + 'đ';
            discountSymbol.textContent = 'đ';
        }

        let min = parseInt(minOrder.value || 0);
        previewMin.textContent = min > 0 ? 'Đơn tối thiểu: ' + min.toLocaleString('vi-VN') + 'đ' : 'Không giới hạn đơn';
    }

    [promoCode, promoTitle, promoDesc, discountType, discountValue, minOrder].forEach(el => {
        el.addEventListener('input', updatePreview);
    });

    generateBtn.addEventListener('click', function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        promoCode.value = 'PET' + result;
        updatePreview();
    });

    updatePreview();
});
</script>
@endpush
@endsection
