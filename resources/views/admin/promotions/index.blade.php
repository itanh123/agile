@extends('layouts.admin')
@section('title', 'Promotions Management')

@section('breadcrumbs', [
    ['label' => 'Promotions']
])

@section('content')
<div class="page-header">
    <h2>Promotions Management</h2>
    <a href="{{ route('admin.promotions.create') }}" class="btn-admin btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Create Promotion
    </a>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $promotions->total() }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Promotions</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center">
            <h3 class="mb-1">{{ $promotions->where('is_active', 1)->count() }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Active</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center" style="background: rgba(239, 68, 68, 0.1);">
            <h3 class="mb-1" style="color: var(--admin-danger);">{{ $expiredPromotions ?? 0 }}</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Expired</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="admin-card text-center" style="background: rgba(16, 185, 129, 0.1);">
            <h3 class="mb-1" style="color: var(--admin-success);">{{ number_format($totalDiscount ?? 0, 0) }}đ</h3>
            <p class="text-muted mb-0" style="font-size: 0.8rem;">Total Discount Value</p>
        </div>
    </div>
</div>

<!-- Promotions Grid -->
@if($promotions->isEmpty())
    <div class="admin-card">
        <div class="empty-state">
            <i class="bi bi-tag"></i>
            <h4>No promotions found</h4>
            <p>Create your first promotion to attract customers.</p>
            <a href="{{ route('admin.promotions.create') }}" class="btn-admin btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Create Promotion
            </a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($promotions as $promotion)
            <div class="col-md-6 col-xl-4">
                <div class="admin-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge-code" style="background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700;">
                            {{ $promotion->code }}
                        </span>
                        <span class="status-badge {{ $promotion->is_active ? 'active' : 'inactive' }}">
                            <i class="bi bi-{{ $promotion->is_active ? 'check-circle' : 'x-circle' }}"></i>
                            {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <h5 class="mb-2">{{ $promotion->title }}</h5>
                    <p class="text-muted mb-3" style="font-size: 0.85rem;">{{ Str::limit($promotion->description, 80) }}</p>
                    
                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div>
                            <span class="fs-4 fw-bold" style="color: var(--admin-secondary);">
                                @if($promotion->discount_type === 'percent')
                                    {{ $promotion->discount_value }}%
                                @else
                                    {{ number_format($promotion->discount_value, 0) }}đ
                                @endif
                            </span>
                            <span class="text-muted" style="font-size: 0.75rem;"> discount</span>
                        </div>
                    </div>
                    
                    <div class="detail-item" style="border-top: 1px solid var(--admin-border); padding-top: 1rem; margin-top: 0.5rem;">
                        <span class="detail-label"><i class="bi bi-calendar me-2"></i>Valid</span>
                        <span class="detail-value">
                            {{ $promotion->start_at ? \Carbon\Carbon::parse($promotion->start_at)->format('d/m') : 'N/A' }} 
                            - 
                            {{ $promotion->end_at ? \Carbon\Carbon::parse($promotion->end_at)->format('d/m/Y') : 'Unlimited' }}
                        </span>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.promotions.show', $promotion) }}" class="btn-admin btn-admin-secondary btn-admin-sm flex-fill">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-admin btn-admin-primary btn-admin-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm" onclick="return confirm('Delete this promotion?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $promotions->withQueryString()->links() }}
    </div>
@endif
@endsection