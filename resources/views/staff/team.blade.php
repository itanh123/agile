@extends('staff.layout')

@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Team</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-people me-2 text-primary"></i>Team Management
            </h3>
            <p class="text-muted mb-0">Quản lý thành viên trong nhóm của bạn.</p>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('staff.dashboard') }}">
            <i class="bi bi-arrow-left me-2"></i>Về Dashboard
        </a>
    </div>

    <!-- Current User Info -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="staff-avatar-lg">
                    {{ strtoupper(substr($currentUser->fullname ?? $currentUser->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-1">{{ $currentUser->fullname ?? $currentUser->name }}</h5>
                    <p class="text-muted mb-0">
                        <span class="badge bg-primary">{{ $currentUser->role?->name ?? 'Staff' }}</span>
                        @if($currentUser->directPermissions->count() > 0)
                            <small class="ms-2">{{ $currentUser->directPermissions->count() }} direct permissions</small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-people me-2 text-primary"></i>Team Members
                <span class="badge bg-primary ms-2">{{ $teamMembers->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($teamMembers->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-people" style="font-size: 48px;"></i>
                    <p class="mt-2 mb-0">Bạn chưa có thành viên nào trong nhóm.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thành Viên</th>
                                <th>Email</th>
                                <th>Permissions</th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="staff-avatar-sm">
                                            {{ strtoupper(substr($currentUser->fullname ?? $currentUser->name, 0, 1)) }}
                                        </div>
                                        <span class="fw-medium">{{ $currentUser->fullname ?? $currentUser->name }} (You)</span>
                                    </div>
                                </td>
                                <td>{{ $currentUser->email }}</td>
                                <td>
                                    @foreach($currentUser->directPermissions as $perm)
                                        <span class="badge bg-success mb-1">{{ $perm->name }}</span>
                                    @endforeach
                                    @if($currentUser->directPermissions->isEmpty())
                                        <span class="text-muted">Role-based</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $teamBookings->where('staff_id', $currentUser->id)->count() }}</span>
                                </td>
                            </tr>
                            @foreach($teamMembers as $member)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="staff-avatar-sm" style="background: #3498db;">
                                                {{ strtoupper(substr($member->fullname ?? $member->name, 0, 1)) }}
                                            </div>
                                            <span class="fw-medium">{{ $member->fullname ?? $member->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        @foreach($member->directPermissions as $perm)
                                            <span class="badge bg-secondary mb-1">{{ $perm->name }}</span>
                                        @endforeach
                                        @if($member->directPermissions->isEmpty())
                                            <span class="text-muted">Role-based</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $teamBookings->where('staff_id', $member->id)->count() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Team Bookings -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-check me-2 text-primary"></i>Team Bookings
            </h5>
        </div>
        <div class="card-body p-0">
            @if($teamBookings->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 48px;"></i>
                    <p class="mt-2 mb-0">Chưa có booking nào của team.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Staff</th>
                                <th>Customer</th>
                                <th>Pet</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teamBookings as $booking)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $booking->booking_code }}</td>
                                    <td>{{ $booking->staff->fullname ?? $booking->staff->name }}</td>
                                    <td>{{ $booking->user->fullname ?? $booking->user->name }}</td>
                                    <td>{{ $booking->pet->name ?? '-' }}</td>
                                    <td>
                                        @if($booking->appointment_at)
                                            {{ $booking->appointment_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'secondary',
                                                'confirmed' => 'info',
                                                'processing' => 'warning',
                                                'completed' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .staff-avatar-lg {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #18bc9c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }
    
    .staff-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #18bc9c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
</style>
@endpush
