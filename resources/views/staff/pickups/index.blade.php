@extends('staff.layout')

@section('title', 'Yêu cầu giao nhận')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-truck"></i> Yêu cầu giao nhận</h2>
        <div>
            <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ phân công</option>
                        <option value="assigned" {{ request('status') === 'assigned' ? 'selected' : '' }}>Đã phân công</option>
                        <option value="picked_up" {{ request('status') === 'picked_up' ? 'selected' : '' }}>Đã nhận thú</option>
                        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Đã giao trả</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pickup List --}}
    @if($pickups->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-inbox display-4"></i>
            <p class="mt-2 mb-0">Không có yêu cầu giao nhận nào</p>
        </div>
    @else
        <div class="row">
            @foreach($pickups as $pickup)
                <div class="col-lg-6 mb-3">
                    <div class="card h-100 {{ $pickup->pickup_staff_id === auth()->id() ? 'border-primary' : '' }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $pickup->status_color }}">{{ $pickup->status_label }}</span>
                            <strong class="text-primary">{{ $pickup->pickup_code }}</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Khách hàng:</strong> {{ $pickup->booking->user->full_name }}</p>
                                    <p class="mb-1"><strong>Pet:</strong> {{ $pickup->booking->pet->name }}</p>
                                    <p class="mb-1"><strong>Booking:</strong> {{ $pickup->booking->booking_code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ Str::limit($pickup->pickup_address, 40) }}</p>
                                    <p class="mb-1"><strong>SDT:</strong> {{ $pickup->pickup_phone }}</p>
                                    <p class="mb-1"><strong>Giờ hẹn:</strong> {{ $pickup->scheduled_pickup_at?->format('H:i d/m') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('staff.pickups.show', $pickup) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Chi tiết
                            </a>
                            @if($pickup->status === 'pending' && $pickup->pickup_staff_id !== auth()->id())
                                <form action="{{ route('staff.pickups.accept', $pickup) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="scheduled_pickup_at" value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-circle"></i> Nhận việc
                                    </button>
                                </form>
                            @endif
                            @if($pickup->pickup_staff_id === auth()->id() && $pickup->status === 'assigned')
                                <form action="{{ route('staff.pickups.picked-up', $pickup) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="bi bi-truck"></i> Đã nhận thú
                                    </button>
                                </form>
                            @endif
                            @if($pickup->pickup_staff_id === auth()->id() && $pickup->status === 'picked_up')
                                <form action="{{ route('staff.pickups.delivered', $pickup) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-all"></i> Đã giao trả
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