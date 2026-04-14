@extends('layouts.admin')
@section('title', 'Services Management')

@section('breadcrumbs', [
    ['label' => 'Services']
])

@section('content')
<div class="page-header">
    <h2>Services Management</h2>
    <a href="{{ route('admin.services.create') }}" class="btn-admin btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Add Service
    </a>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.services.index') }}" class="filter-bar">
        <div class="form-group">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" name="q" class="form-control-admin" placeholder="Search services..." value="{{ request('q') }}">
            </div>
        </div>
        <div class="form-group">
            <select name="type" class="form-select-admin">
                <option value="">All Types</option>
                @foreach(['grooming', 'vaccination', 'spa', 'checkup', 'surgery'] as $type)
                    <option value="{{ $type }}" @selected(request('type') == $type)>{{ ucfirst($type) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <select name="status" class="form-select-admin">
                <option value="">All Status</option>
                <option value="1" @selected(request('status') == '1')>Active</option>
                <option value="0" @selected(request('status') == '0')>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn-admin btn-admin-secondary">
            <i class="bi bi-funnel"></i> Filter
        </button>
    </form>
</div>

<!-- Services Grid -->
@if($services->isEmpty())
    <div class="admin-card">
        <div class="empty-state">
            <i class="bi bi-gem"></i>
            <h4>No services found</h4>
            <p>Create your first service to get started.</p>
            <a href="{{ route('admin.services.create') }}" class="btn-admin btn-admin-primary">
                <i class="bi bi-plus-lg"></i> Add Service
            </a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($services as $service)
            <div class="col-md-6 col-xl-4">
                <div class="admin-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge-type" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); padding: 0.25rem 0.625rem; border-radius: 0.5rem; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">
                                {{ $service->service_type }}
                            </span>
                        </div>
                        <span class="status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                            <i class="bi bi-{{ $service->is_active ? 'check-circle' : 'x-circle' }}"></i>
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <h5 class="mb-2">{{ $service->name }}</h5>
                    <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                        {{ Str::limit($service->description, 100) }}
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="fs-4 fw-bold" style="color: var(--admin-success);">{{ number_format($service->price, 0) }}đ</span>
                        </div>
                        <div class="text-muted" style="font-size: 0.8rem;">
                            <i class="bi bi-clock me-1"></i>{{ $service->duration_minutes ?? 30 }} min
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.services.show', $service) }}" class="btn-admin btn-admin-secondary btn-admin-sm flex-fill">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn-admin btn-admin-primary btn-admin-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm" onclick="return confirm('Delete this service?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-4">
        {{ $services->withQueryString()->links() }}
    </div>
@endif
@endsection
