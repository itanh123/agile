@extends('layouts.app')

@section('title', 'Yêu cầu giao nhận')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bi bi-truck"></i> Yêu cầu giao nhận thú cưng</h2>

    @if($pickups->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox display-4"></i>
            <p class="mt-2 mb-0">Bạn chưa có yêu cầu giao nhận nào</p>
            <a href="{{ route('customer.bookings.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-calendar-check"></i> Xem lịch hẹn của tôi
            </a>
        </div>
    @else
        <div class="row">
            @foreach($pickups as $pickup)
                <div class="col-lg-6 mb-3">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $pickup->status_color }}">{{ $pickup->status_label }}</span>
                            <strong class="text-primary">{{ $pickup->pickup_code }}</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Pet:</strong> {{ $pickup->booking->pet->name }}</p>
                            <p><strong>Booking:</strong> {{ $pickup->booking->booking_code }}</p>
                            <p><strong>Địa chỉ:</strong> {{ Str::limit($pickup->pickup_address, 50) }}</p>
                            <p><strong>Giờ hẹn:</strong> {{ $pickup->scheduled_pickup_at?->format('H:i d/m/Y') }}</p>
                            @if($pickup->pickupStaff)
                                <p><strong>Nhân viên:</strong> {{ $pickup->pickupStaff->full_name }}</p>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('customer.pickups.show', $pickup) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Chi tiết
                            </a>
                            @if(in_array($pickup->status, ['pending', 'assigned']))
                                <form action="{{ route('customer.pickups.cancel', $pickup) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn chắc chắn muốn hủy?')">
                                        <i class="bi bi-x-circle"></i> Hủy
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $pickups->links() }}
        </div>
    @endif
</div>
@endsection