@extends('layouts.app')

@section('title', 'Chọn phương thức thanh toán - #' . $booking->booking_code)

@push('styles')
<style>
    .payment-method-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #e9ecef;
    }
    .payment-method-card:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .payment-method-card.selected {
        border-color: #0d6efd;
        background: #f0f9ff;
    }
    .payment-icon {
        font-size: 2.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.bookings.show', $booking) }}">Booking #{{ $booking->booking_code }}</a></li>
            <li class="breadcrumb-item active">Thanh toán</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Chọn phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Số tiền cần thanh toán:</strong>
                            </div>
                            <h4 class="mb-0 text-primary">{{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</h4>
                        </div>
                        <hr>
                        <div class="row small">
                            <div class="col-6">
                                <strong>Mã booking:</strong> {{ $booking->booking_code }}
                            </div>
                            <div class="col-6">
                                <strong>Ngày hẹn:</strong> {{ $booking->appointment_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('customer.payments.process', $booking) }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="method" id="selectedMethod" value="">

                        <div class="row g-4 justify-content-center">
                            {{-- VNPay --}}
                            <div class="col-md-5">
                                <div class="card payment-method-card h-100 shadow-sm border-2" data-method="vnpay">
                                    <div class="card-body text-center p-4">
                                        <div class="payment-icon mb-3">
                                            <img src="https://sandbox.vnpayment.vn/paymentv2/Images/brands/logo-vnpay.png" alt="VNPay" style="height: 50px;">
                                        </div>
                                        <h5 class="fw-bold">Thanh toán VNPay</h5>
                                        <p class="text-muted small mb-0">Hỗ trợ thẻ ATM, Internet Banking, QR Code</p>
                                        <span class="badge bg-success mt-3 px-3">Khuyên dùng</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Tiền mặt --}}
                            <div class="col-md-5">
                                <div class="card payment-method-card h-100 shadow-sm border-2" data-method="cash">
                                    <div class="card-body text-center p-4">
                                        <div class="payment-icon mb-3" style="font-size: 50px;">💵</div>
                                        <h5 class="fw-bold">Tiền mặt</h5>
                                        <p class="text-muted small mb-0">Thanh toán trực tiếp tại cửa hàng</p>
                                        <span class="badge bg-secondary mt-3 px-3">Tại quầy</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                                <i class="bi bi-credit-card"></i> Tiếp tục thanh toán
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.payment-method-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedMethod').value = this.dataset.method;
        document.getElementById('submitBtn').disabled = false;
    });
});
</script>
@endpush