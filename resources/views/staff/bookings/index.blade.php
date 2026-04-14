@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Assigned Bookings</h3>
            <p class="text-muted mb-0">Quản lý đầy đủ các booking được giao cho bạn.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.dashboard') }}">Về dashboard</a>
    </div>

    <div class="table-responsive mb-4">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Code</th>
                    <th>Khách</th>
                    <th>Pet</th>
                    <th>Appointment</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_code }}</td>
                        <td>{{ $booking->user->full_name ?? $booking->user->name }}</td>
                        <td>{{ $booking->pet->name ?? '-' }}</td>
                        <td>{{ optional($booking->appointment_at)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ ucfirst($booking->payment_status ?? 'pending') }}</td>
                        <td>
                            <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'processing' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td class="text-end"><a href="{{ route('staff.bookings.show', $booking) }}" class="btn btn-sm btn-primary">Chi tiết</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Không có booking nào được giao.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $bookings->links() }}
@endsection
