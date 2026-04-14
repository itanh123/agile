@extends('layouts.admin')
@section('title', 'Service Details')
@section('breadcrumbs', [
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => $service->name]
])

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <!-- Service Info Card -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <span class="badge-type" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); padding: 0.375rem 0.875rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                        {{ $service->service_type }}
                    </span>
                </div>
                <span class="status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                    <i class="bi bi-{{ $service->is_active ? 'check-circle' : 'x-circle' }}"></i>
                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <h3 class="mb-3">{{ $service->name }}</h3>
            <p class="text-muted mb-4" style="line-height: 1.6;">{{ $service->description }}</p>
            
            <div class="detail-section">
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-currency-dollar me-2"></i>Price</span>
                    <span class="detail-value fs-5 fw-bold" style="color: var(--admin-success);">{{ number_format($service->price, 0) }}đ</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-clock me-2"></i>Duration</span>
                    <span class="detail-value">{{ $service->duration_minutes ?? 30 }} minutes</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-calendar-plus me-2"></i>Created</span>
                    <span class="detail-value">{{ $service->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.services.edit', $service) }}" class="btn-admin btn-admin-primary flex-fill">
                    <i class="bi bi-pencil"></i> Edit Service
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <!-- Service Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-bar-chart" style="font-size: 1.5rem; color: var(--admin-primary);"></i>
                    <h3 class="mt-2 mb-1">{{ $totalBookings ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Bookings</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-currency-dollar" style="font-size: 1.5rem; color: var(--admin-success);"></i>
                    <h3 class="mt-2 mb-1">{{ number_format($totalRevenue ?? 0, 0) }}đ</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Revenue</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-graph-up" style="font-size: 1.5rem; color: var(--admin-info);"></i>
                    <h3 class="mt-2 mb-1">{{ $thisMonthBookings ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">This Month</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings with this Service -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Recent Bookings</h5>
            </div>
            @if(isset($service->bookings) && $service->bookings->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Pet</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($service->bookings->take(5) as $booking)
                                <tr>
                                    <td><strong>{{ $booking->booking_code }}</strong></td>
                                    <td>{{ $booking->user->full_name ?? $booking->user->name }}</td>
                                    <td>{{ $booking->pet->name ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $booking->status }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-admin btn-admin-secondary btn-admin-sm">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="bi bi-calendar-x"></i>
                    <h4>No bookings yet</h4>
                    <p>This service hasn't been booked yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.services.index') }}" class="btn-admin btn-admin-secondary">
        <i class="bi bi-arrow-left"></i> Back to Services
    </a>
</div>
@endsection
