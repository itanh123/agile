@extends('layouts.admin')
@section('title', 'Booking Details')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.bookings.index') }}">Bookings</a>
<i class="bi bi-chevron-right"></i>
<span>{{ $booking->booking_code }}</span>
@endpush

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Booking Info -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <span class="badge-code" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 600;">
                        {{ $booking->booking_code }}
                    </span>
                </div>
                <span class="status-badge {{ $booking->status }}">
                    <i class="bi bi-{{ $booking->status === 'completed' ? 'check-circle' : ($booking->status === 'cancelled' ? 'x-circle' : ($booking->status === 'processing' ? 'gear' : 'clock')) }}"></i>
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            
            <h4 class="mb-4">Booking Information</h4>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-section-title">Customer</h6>
                        <div class="detail-item">
                            <span class="detail-label">Name</span>
                            <span class="detail-value">{{ $booking->user->full_name ?? $booking->user->name }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $booking->user->email }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value">{{ $booking->user->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="detail-section">
                        <h6 class="detail-section-title">Pet</h6>
                        <div class="detail-item">
                            <span class="detail-label">Name</span>
                            <span class="detail-value">{{ $booking->pet->name ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Species</span>
                            <span class="detail-value">{{ $booking->pet->species ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Breed</span>
                            <span class="detail-value">{{ $booking->pet->breed ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="detail-section mt-4">
                <h6 class="detail-section-title">Booking Details</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Created</span>
                            <span class="detail-value">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Appointment</span>
                            <span class="detail-value">{{ $booking->appointment_at ? $booking->appointment_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-item">
                            <span class="detail-label">Total Amount</span>
                            <span class="detail-value fs-5 fw-bold" style="color: var(--admin-success);">{{ number_format($booking->total_amount ?? $booking->total, 0) }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Services -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Services</h5>
            </div>
            @if($booking->services->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->services as $service)
                                <tr>
                                    <td><strong>{{ $service->name }}</strong></td>
                                    <td>{{ ucfirst($service->service_type) }}</td>
                                    <td>{{ $service->duration_minutes ?? 30 }} min</td>
                                    <td>{{ number_format($service->price, 0) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No services assigned.</p>
            @endif
        </div>
        
        <!-- Notes -->
        @if($booking->notes ?? $booking->note)
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5 class="admin-card-title">Notes</h5>
                </div>
                <p class="text-muted mb-0">{{ $booking->notes ?? $booking->note }}</p>
            </div>
        @endif
    </div>
    
    <div class="col-lg-4">
        <!-- Assign Staff -->
        <div class="admin-card mb-4">
            <h5 class="admin-card-title mb-3">Assign Staff</h5>
            @if(isset($staffUsers) && $staffUsers->isNotEmpty())
                <form method="POST" action="{{ route('admin.bookings.assign-staff', $booking) }}" class="mb-3">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <select name="staff_id" class="form-select-admin">
                            <option value="">Select Staff</option>
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->id }}" @selected($booking->staff_id == $staff->id)>
                                    {{ $staff->full_name ?? $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-admin btn-admin-primary w-100">
                        <i class="bi bi-person-check"></i> Assign Staff
                    </button>
                </form>
            @else
                <p class="text-muted mb-0">No staff available.</p>
            @endif
        </div>
        
        <!-- Update Status -->
        <div class="admin-card mb-4">
            <h5 class="admin-card-title mb-3">Update Status</h5>
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <select name="status" class="form-select-admin">
                        @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($booking->status == $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <textarea name="note" class="form-control-admin" rows="2" placeholder="Add a note...">{{ $booking->note ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn-admin btn-admin-warning w-100">
                    <i class="bi bi-check-lg"></i> Update Status
                </button>
            </form>
        </div>
        
        <!-- Actions -->
        <div class="admin-card">
            <h5 class="admin-card-title mb-3">Actions</h5>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                @if($booking->user)
                    <a href="{{ route('admin.users.show', $booking->user) }}" class="btn-admin btn-admin-secondary w-100">
                        <i class="bi bi-person"></i> View Customer
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
