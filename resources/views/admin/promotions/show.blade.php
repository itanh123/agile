@extends('layouts.admin')
@section('title', 'Promotion Details')
@section('breadcrumbs', [
    ['label' => 'Promotions', 'url' => route('admin.promotions.index')],
    ['label' => $promotion->code]
])

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <!-- Promotion Info Card -->
        <div class="admin-card mb-4">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <span class="badge-code" style="background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 1rem; font-weight: 700;">
                    {{ $promotion->code }}
                </span>
                <span class="status-badge {{ $promotion->is_active ? 'active' : 'inactive' }}">
                    <i class="bi bi-{{ $promotion->is_active ? 'check-circle' : 'x-circle' }}"></i>
                    {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <h3 class="mb-3">{{ $promotion->title }}</h3>
            <p class="text-muted mb-4" style="line-height: 1.6;">{{ $promotion->description }}</p>
            
            <div class="detail-section">
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-percent me-2"></i>Discount</span>
                    <span class="detail-value fs-4 fw-bold" style="color: var(--admin-secondary);">
                        @if($promotion->discount_type === 'percent')
                            {{ $promotion->discount_value }}%
                        @else
                            {{ number_format($promotion->discount_value, 0) }}đ
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-calendar-start me-2"></i>Start Date</span>
                    <span class="detail-value">{{ $promotion->start_at ? \Carbon\Carbon::parse($promotion->start_at)->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-calendar-end me-2"></i>End Date</span>
                    <span class="detail-value">{{ $promotion->end_at ? \Carbon\Carbon::parse($promotion->end_at)->format('d/m/Y H:i') : 'Unlimited' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-clock-history me-2"></i>Created</span>
                    <span class="detail-value">{{ $promotion->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-admin btn-admin-primary flex-fill">
                    <i class="bi bi-pencil"></i> Edit Promotion
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-7">
        <!-- Usage Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-ticket" style="font-size: 1.5rem; color: var(--admin-primary);"></i>
                    <h3 class="mt-2 mb-1">{{ $promotion->usage_count ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Times Used</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-people" style="font-size: 1.5rem; color: var(--admin-info);"></i>
                    <h3 class="mt-2 mb-1">{{ $promotion->unique_users ?? 0 }}</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Unique Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card text-center">
                    <i class="bi bi-currency-dollar" style="font-size: 1.5rem; color: var(--admin-success);"></i>
                    <h3 class="mt-2 mb-1">{{ number_format($promotion->total_saved ?? 0, 0) }}đ</h3>
                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Saved</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Usage -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5 class="admin-card-title">Recent Usage</h5>
            </div>
            @if(isset($promotion->usages) && $promotion->usages->isNotEmpty())
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Booking</th>
                                <th>Discount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promotion->usages->take(10) as $usage)
                                <tr>
                                    <td>{{ $usage->user->full_name ?? $usage->user->name }}</td>
                                    <td>{{ $usage->booking->booking_code ?? '-' }}</td>
                                    <td>{{ number_format($usage->discount_amount, 0) }}đ</td>
                                    <td>{{ $usage->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-4">
                    <i class="bi bi-ticket-perforated"></i>
                    <h4>No usage yet</h4>
                    <p>This promotion hasn't been used yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.promotions.index') }}" class="btn-admin btn-admin-secondary">
        <i class="bi bi-arrow-left"></i> Back to Promotions
    </a>
</div>
@endsection
