@extends('layouts.app')

@section('title', 'Thanh toán chuyển khoản - #' . $booking->booking_code)

@push('styles')
<style>
    .bank-card {
        border: 2px solid #e9ecef;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .bank-card:hover {
        border-color: #0d6efd;
    }
    .copy-btn {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.bookings.show', $booking) }}">Booking #{{ $booking->booking_code }}</a></li>
            <li class="breadcrumb-item active">Chuyển khoản</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="alert alert-info text-center">
                <h5>Số tiền cần thanh toán</h5>
                <h3 class="text-primary mb-0">{{ number_format($booking->total_amount, 0, ',', '.') }} VNĐ</h3>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-info-circle"></i>
                <strong>Nội dung chuyển khoản:</strong> <code class="fs-5">{{ $booking->booking_code }}</code>
                <button class="btn btn-sm btn-outline-primary copy-btn ms-2" onclick="copyToClipboard('{{ $booking->booking_code }}')">
                    <i class="bi bi-clipboard"></i> Sao chép
                </button>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bank"></i> Thông tin tài khoản ngân hàng</h5>
                </div>
                <div class="card-body">
                    @foreach($bankAccounts as $key => $bank)
                        <div class="bank-card p-3 mb-3 {{ !$loop->first ? 'mt-3 border-top pt-3' : '' }}">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">{{ strtoupper($bank['bank']) }}</span>
                            </div>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td width="40%"><strong>Tên tài khoản:</strong></td>
                                    <td>{{ $bank['name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Số tài khoản:</strong></td>
                                    <td>
                                        <code class="fs-6">{{ $bank['account'] }}</code>
                                        <button class="btn btn-sm btn-outline-secondary copy-btn" onclick="copyToClipboard('{{ $bank['account'] }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Chi nhánh:</strong></td>
                                    <td>{{ $bank['branch'] }}</td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="alert alert-secondary mt-4">
                <h6><i class="bi bi-clock-history"></i> Lưu ý:</h6>
                <ul class="mb-0 small">
                    <li>Đơn hàng sẽ được xác nhận sau khi chúng tôi nhận được thanh toán (1-5 phút làm việc).</li>
                    <li>Nếu sau 24h chưa nhận được thanh toán, đơn hàng sẽ tự động bị hủy.</li>
                    <li>Đảm bảo nhập đúng nội dung chuyển khoản để chúng tôi xác nhận nhanh hơn.</li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('customer.bookings.show', $booking) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại booking
                </a>
                <form action="{{ route('customer.payments.confirm-transfer', $booking) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Tôi đã chuyển khoản
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép: ' + text);
    });
}
</script>
@endpush