@extends('layouts.app')
@section('content')
    <div class="animate-fade-up">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="badge-premium mb-2 d-inline-block">Member Area</span>
                <h1 class="text-white fw-bold mb-0">My Service History</h1>
            </div>
            <a href="{{ route('customer.bookings.create') }}" class="btn-premium">
                <i class="bi bi-calendar-plus me-1"></i> New Booking
            </a>
        </div>

        <div class="glass-card p-4">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" style="--bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.02)">
                    <thead>
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="border-0 ps-0">Booking Details</th>
                            <th class="border-0">Pet</th>
                            <th class="border-0">Appointment</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end pe-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td class="border-0 ps-0 py-3">
                                    <div class="text-white fw-bold mb-1">{{ $booking->booking_code }}</div>
                                    <div class="text-muted small">Total: {{ number_format($booking->total_amount, 0) }} VND</div>
                                </td>
                                <td class="border-0 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-5">{{ $booking->pet?->category?->slug === 'dog' ? '🐶' : ($booking->pet?->category?->slug === 'cat' ? '🐱' : '🐾') }}</span>
                                        <span class="text-white">{{ $booking->pet->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="border-0 py-3">
                                    <div class="text-white small">{{ $booking->appointment_at->format('M d, Y') }}</div>
                                    <div class="text-muted small">{{ $booking->appointment_at->format('H:i') }}</div>
                                </td>
                                <td class="border-0 py-3">
                                    @php
                                        $statusColor = match($booking->status) {
                                            'pending' => '#f59e0b',
                                            'confirmed' => '#6366f1',
                                            'completed' => '#10b981',
                                            'cancelled' => '#ef4444',
                                            default => '#94a3b8'
                                        };
                                    @endphp
                                    <span class="badge-premium py-1 px-3 d-inline-block" style="background: {{ $statusColor }}20; color: {{ $statusColor }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="border-0 text-end pe-0 py-3">
                                    <a class="btn-premium py-1 px-3 fs-6" href="{{ route('customer.bookings.show', $booking) }}">View Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="border-0 text-center py-5">
                                    <div class="text-muted mb-3">No bookings found in your history.</div>
                                    <a href="{{ route('customer.bookings.create') }}" class="btn-outline-premium btn-sm">Start your first booking</a>
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
