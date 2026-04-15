@extends('layouts.admin')
@section('title', 'Create Promotion')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.promotions.index') }}">Promotions</a>
<i class="bi bi-chevron-right"></i>
<span>Create</span>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-form">
            <form method="POST" action="{{ route('admin.promotions.store') }}">
                @csrf
                
                <h4 class="mb-4">Promotion Information</h4>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Promotion Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control-admin" 
                               value="{{ old('code') }}" 
                               placeholder="e.g., SUMMER2024" required style="text-transform: uppercase;">
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control-admin" 
                               value="{{ old('title') }}" 
                               placeholder="e.g., Summer Sale 20% Off" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-select-admin" required>
                            <option value="percent" @selected(old('discount_type') == 'percent')>Percentage (%)</option>
                            <option value="fixed" @selected(old('discount_type') == 'fixed')>Fixed Amount (VND)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                        <input type="number" name="discount_value" class="form-control-admin" 
                               value="{{ old('discount_value') }}" 
                               placeholder="e.g., 20" min="0" required>
                        @error('discount_value')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_at" class="form-control-admin" 
                               value="{{ old('start_at') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_at" class="form-control-admin" 
                               value="{{ old('end_at') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select-admin">
                            <option value="1" @selected(old('is_active', 1) == 1)>Active</option>
                            <option value="0" @selected(old('is_active', 1) == 0)>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control-admin" rows="3" 
                                  placeholder="Describe the promotion...">{{ old('description') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="bi bi-check-lg"></i> Create Promotion
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn-admin btn-admin-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-card">
            <h5 class="admin-card-title mb-3">Tips</h5>
            <div class="detail-section">
                <div class="mb-3">
                    <strong style="color: var(--admin-primary);">Percentage Discount</strong>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Best for seasonal sales. Example: 20% off all grooming services.</p>
                </div>
                <div class="mb-3">
                    <strong style="color: var(--admin-success);">Fixed Amount</strong>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Great for new customer offers. Example: 50,000 VND off first booking.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
