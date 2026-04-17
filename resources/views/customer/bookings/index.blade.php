@extends('layouts.app')
@section('title', 'Lịch sử đặt lịch')

@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Khu vực thành viên</span>
                <h1 class="text-white fw-bold mb-0">Lịch Sử Đặt Lịch</h1>
            </div>
            <a href="{{ route('customer.bookings.create') }}" class="btn-premium">
                <i class="bi bi-calendar-plus me-1"></i> Đặt lịch mới
            </a>
        </div>

        <div class="glass-card p-4">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.02)">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="border-0 ps-0">Chi tiết dịch vụ</th>
                            <th class="border-0">Thú cưng</th>
                            <th class="border-0">Thời gian hẹn</th>
                            <th class="border-0">Trạng thái</th>
                            <th class="border-0 text-end pe-0">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="border-0 ps-0 py-3">
                                    <div class="text-white fw-bold mb-1">{{ $booking->booking_code }}</div>
                                    <div class="text-muted small">Tổng thanh toán: {{ number_format($booking->total_amount, 0) }}đ</div>
                                </td>
                                <td class="border-0 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="p-2 bg-light bg-opacity-10 rounded-circle text-center" style="width: 35px; height: 35px; line-height: 20px;">
                                            <span class="fs-6">{{ $booking->pet?->category?->slug === 'dog' ? '🐶' : ($booking->pet?->category?->slug === 'cat' ? '🐱' : '🐾') }}</span>
                                        </div>
                                        <span class="text-white fw-medium">{{ $booking->pet->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="border-0 py-3">
                                    <div class="text-white fw-medium">{{ $booking->appointment_at->format('d/m/Y') }}</div>
                                    <div class="text-primary small fw-bold">{{ $booking->appointment_at->format('H:i') }}</div>
                                </td>
                                <td class="border-0 py-3">
                                    @php
                                        $statusMap = [
                                            'pending' => ['label' => 'Chờ xử lý', 'color' => '#f59e0b'],
                                            'confirmed' => ['label' => 'Đã xác nhận', 'color' => '#6366f1'],
                                            'processing' => ['label' => 'Đang làm', 'color' => '#3b82f6'],
                                            'completed' => ['label' => 'Hoàn thành', 'color' => '#10b981'],
                                            'cancelled' => ['label' => 'Đã hủy', 'color' => '#ef4444'],
                                        ];
                                        $srvStatus = $statusMap[$booking->status] ?? ['label' => $booking->status, 'color' => '#94a3b8'];
                                    @endphp
                                    <span class="badge-premium py-1 px-3 d-inline-block small fw-bold" style="background: {{ $srvStatus['color'] }}20; color: {{ $srvStatus['color'] }}">
                                        {{ $srvStatus['label'] }}
                                    </span>
                                </td>
                                <td class="border-0 text-end pe-0 py-3">
                                    <div class="d-flex gap-2 justify-content-end">
                                        @if($booking->status === 'completed' && !$booking->review)
                                            <a class="btn-premium py-1 px-3 fs-6" style="background: linear-gradient(135deg, #10b981, #059669);" href="{{ route('customer.reviews.create', ['booking_id' => $booking->id]) }}">
                                                <i class="bi bi-star-fill me-1"></i> Đánh giá
                                            </a>
                                        @endif
                                        <a class="btn-premium py-1 px-3 fs-6" href="{{ route('customer.bookings.show', $booking) }}">
                                            <i class="bi bi-eye"></i> Chi tiết
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border-0 text-center py-5">
                                    <div class="text-muted mb-3">Bạn chưa có lịch đặt dịch vụ nào trong lịch sử.</div>
                                    <a href="{{ route('customer.bookings.create') }}" class="btn-outline-premium btn-sm">Bắt đầu đặt lịch ngay</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $bookings->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
