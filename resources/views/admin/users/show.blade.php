@extends('layouts.admin')
@section('title', 'User Details')
@section('breadcrumbs', [
    ['label' => 'Users', 'url' => route('admin.users.index')],
    ['label' => $user->full_name ?? $user->name]
])

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="admin-card">
            <div class="text-center mb-4">
                <div class="table-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($user->full_name ?? $user->name ?? 'U', 0, 1)) }}
                </div>
                <h4 class="mt-3 mb-1">{{ $user->full_name ?? $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                @if($user->role)
                    <span class="badge-role" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); padding: 0.375rem 0.875rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 600;">
                        <i class="bi bi-shield-check me-1"></i>{{ $user->role->name }}
                    </span>
                @endif
            </div>
            
            <div class="detail-section">
                <h6 class="detail-section-title">Contact Information</h6>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-envelope me-2"></i>Email</span>
                    <span class="detail-value">{{ $user->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-telephone me-2"></i>Phone</span>
                    <span class="detail-value">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-geo-alt me-2"></i>Address</span>
                    <span class="detail-value">{{ $user->address ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
            <!-- User Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-calendar-check" style="font-size: 1.5rem; color: var(--admin-info);"></i>
                    <h3 class="mt-2 mb-1">{{ $bookingsCount ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Bookings</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-clock-history" style="font-size: 1.5rem; color: var(--admin-warning);"></i>
                    <h3 class="mt-2 mb-1">{{ $pendingBookings ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Pending</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-check-circle" style="font-size: 1.5rem; color: var(--admin-success);"></i>
                    <h3 class="mt-2 mb-1">{{ $completedBookings ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Completed</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Recent Bookings</h5>
            </div>
            @if($user->bookings->isEmpty())
                <div class="empty-state py-4">
                    <i class="bi bi-calendar-x"></i>
                    <h4>No bookings yet</h4>
                    <p>This user hasn't made any bookings.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Pet</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->bookings->take(5) as $booking)
                                <tr>
                                    <td><strong>{{ $booking->booking_code }}</strong></td>
                                    <td>{{ $booking->pet->name ?? '-' }}</td>
                                    <td>{{ $booking->services->count() }} services</td>
                                    <td>
                                        <span class="status-badge {{ $booking->status }}">
                                            <i class="bi bi-{{ $booking->status === 'completed' ? 'check-circle' : ($booking->status === 'cancelled' ? 'x-circle' : 'clock') }}"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('d/m/Y') }}</td>
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
            @endif
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin btn-admin-primary">
        <i class="bi bi-pencil"></i> Edit User
    </a>
    <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-secondary">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>
</div>
@endsection
