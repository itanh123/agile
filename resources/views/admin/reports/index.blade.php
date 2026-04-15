@extends('layouts.admin')
@section('title', 'Reports & Analytics')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Reports</span>
@endpush

@section('content')
<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="bi bi-calendar-check" style="font-size: 2rem; color: var(--admin-info);"></i>
            <h3 class="mt-2 mb-1">{{ $totalBookings ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Bookings</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="bi bi-currency-dollar" style="font-size: 2rem; color: var(--admin-success);"></i>
            <h3 class="mt-2 mb-1">{{ number_format($totalRevenue ?? 0, 0) }}đ</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Revenue</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="bi bi-people" style="font-size: 2rem; color: var(--admin-primary);"></i>
            <h3 class="mt-2 mb-1">{{ $totalCustomers ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Customers</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <i class="bi bi-graph-up-arrow" style="font-size: 2rem; color: var(--admin-warning);"></i>
            <h3 class="mt-2 mb-1">{{ number_format($avgBookingValue ?? 0, 0) }}đ</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Avg. Booking Value</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Booking Status Summary -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Booking Status Summary</h5>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Percentage</th>
                            <th>Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = $statusCounts->sum('total') ?: 1; @endphp
                        @foreach($statusCounts as $status)
                            <tr>
                                <td>
                                    <span class="status-badge {{ $status->status }}">
                                        <i class="bi bi-{{ $status->status === 'completed' ? 'check-circle' : ($status->status === 'cancelled' ? 'x-circle' : ($status->status === 'processing' ? 'gear' : 'clock')) }}"></i>
                                        {{ ucfirst($status->status) }}
                                    </span>
                                </td>
                                <td><strong>{{ $status->total }}</strong></td>
                                <td>{{ round(($status->total / $total) * 100, 1) }}%</td>
                                <td>
                                    <div class="progress-bar-custom" style="width: 100px; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                                        <div style="width: {{ ($status->total / $total) * 100 }}%; height: 100%; background: var(--admin-{{ $status->status === 'completed' ? 'success' : ($status->status === 'cancelled' ? 'danger' : ($status->status === 'processing' ? 'primary' : 'info')) }}); border-radius: 4px;"></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Revenue by Month -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Revenue by Month</h5>
            </div>
            @if($revenueByMonth->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Revenue</th>
                                <th>Bookings</th>
                                <th>Visual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $maxRevenue = $revenueByMonth->max('total') ?: 1; @endphp
                            @foreach($revenueByMonth as $month)
                                <tr>
                                    <td><strong>{{ $month->month }}</strong></td>
                                    <td>{{ number_format($month->total, 0) }}đ</td>
                                    <td>{{ $month->bookings ?? '-' }}</td>
                                    <td>
                                        <div class="progress-bar-custom" style="width: 100px; height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                                            <div style="width: {{ ($month->total / $maxRevenue) * 100 }}%; height: 100%; background: linear-gradient(90deg, var(--admin-success), var(--admin-info)); border-radius: 4px;"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="bi bi-graph-up"></i>
                    <h4>No revenue data</h4>
                    <p>Revenue data will appear here once bookings are completed.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Most Used Services -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Most Used Services</h5>
            </div>
            @if($mostUsedServices->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mostUsedServices as $index => $service)
                                <tr>
                                    <td>
                                        <span class="badge-rank" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $service->name }}</strong></td>
                                    <td>
                                        <span style="background: rgba(6, 182, 212, 0.15); color: var(--admin-info); padding: 0.2rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">
                                            {{ ucfirst($service->service_type) }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $service->total }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="bi bi-gem"></i>
                    <h4>No service data</h4>
                    <p>Service usage will appear here once bookings are made.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Top Customers -->
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Top Customers</h5>
            </div>
            @if(isset($topCustomers) && $topCustomers->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Bookings</th>
                                <th>Total Spent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCustomers as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="table-avatar" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                                {{ strtoupper(substr($customer->full_name ?? $customer->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <span>{{ $customer->full_name ?? $customer->name }}</span>
                                        </div>
                                    </td>
                                    <td><strong>{{ $customer->bookings_count }}</strong></td>
                                    <td style="color: var(--admin-success);">{{ number_format($customer->total_spent, 0) }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="bi bi-star"></i>
                    <h4>No customer data</h4>
                    <p>Top customers will appear here based on booking activity.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.progress-bar-custom {
    display: block;
}
</style>
@endsection
