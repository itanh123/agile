@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="admin-card stats-card users">
            <i class="bi bi-people stats-icon"></i>
            <div class="admin-card-header">
                <p class="admin-card-title">Total Users</p>
            </div>
            <h2 class="admin-card-value">{{ number_format($totalUsers) }}</h2>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.8rem;">Người dùng đã đăng ký</p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-card stats-card bookings">
            <i class="bi bi-calendar-check stats-icon"></i>
            <div class="admin-card-header">
                <p class="admin-card-title">Total Bookings</p>
            </div>
            <h2 class="admin-card-value">{{ number_format($totalBookings) }}</h2>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.8rem;">Lịch hẹn được tạo</p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-card stats-card services">
            <i class="bi bi-gem stats-icon"></i>
            <div class="admin-card-header">
                <p class="admin-card-title">Active Services</p>
            </div>
            <h2 class="admin-card-value">{{ number_format($totalServices) }}</h2>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.8rem;">Dịch vụ đang hoạt động</p>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-card stats-card revenue">
            <i class="bi bi-currency-dollar stats-icon"></i>
            <div class="admin-card-header">
                <p class="admin-card-title">Total Revenue</p>
            </div>
            <h2 class="admin-card-value">{{ number_format($totalRevenue, 0) }}đ</h2>
            <p class="text-muted mt-1 mb-0" style="font-size: 0.8rem;">Doanh thu tổng cộng</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h4 class="admin-card-title">Quick Actions</h4>
            </div>
            <div class="quick-actions">
                <a href="{{ route('admin.users.create') }}" class="quick-action-card">
                    <i class="bi bi-person-plus"></i>
                    <h5>Add User</h5>
                </a>
                <a href="{{ route('admin.services.create') }}" class="quick-action-card">
                    <i class="bi bi-plus-circle"></i>
                    <h5>New Service</h5>
                </a>
                <a href="{{ route('admin.promotions.create') }}" class="quick-action-card">
                    <i class="bi bi-tag"></i>
                    <h5>Create Voucher</h5>
                </a>
                <a href="{{ route('admin.reports.index') }}" class="quick-action-card">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <h5>View Reports</h5>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Management Links -->
<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.users.index') }}" class="admin-card text-decoration-none d-block">
            <div class="d-flex align-items-center gap-3">
                <div class="quick-action-icon" style="background: rgba(99, 102, 241, 0.15);">
                    <i class="bi bi-people" style="color: var(--admin-primary);"></i>
                </div>
                <div>
                    <h5 class="mb-1">Users</h5>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Quản lý người dùng</p>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--admin-text-dim);"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.services.index') }}" class="admin-card text-decoration-none d-block">
            <div class="d-flex align-items-center gap-3">
                <div class="quick-action-icon" style="background: rgba(16, 185, 129, 0.15);">
                    <i class="bi bi-gem" style="color: var(--admin-success);"></i>
                </div>
                <div>
                    <h5 class="mb-1">Services</h5>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Quản lý dịch vụ</p>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--admin-text-dim);"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.bookings.index') }}" class="admin-card text-decoration-none d-block">
            <div class="d-flex align-items-center gap-3">
                <div class="quick-action-icon" style="background: rgba(6, 182, 212, 0.15);">
                    <i class="bi bi-calendar-check" style="color: var(--admin-info);"></i>
                </div>
                <div>
                    <h5 class="mb-1">Bookings</h5>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Quản lý lịch hẹn</p>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--admin-text-dim);"></i>
            </div>
        </a>
    </div>
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('admin.promotions.index') }}" class="admin-card text-decoration-none d-block">
            <div class="d-flex align-items-center gap-3">
                <div class="quick-action-icon" style="background: rgba(236, 72, 153, 0.15);">
                    <i class="bi bi-tag" style="color: var(--admin-secondary);"></i>
                </div>
                <div>
                    <h5 class="mb-1">Vouchers</h5>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Quản lý mã giảm giá</p>
                </div>
                <i class="bi bi-chevron-right ms-auto" style="color: var(--admin-text-dim);"></i>
            </div>
        </a>
    </div>
</div>

<style>
    .quick-action-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>
@endsection
