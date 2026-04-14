@extends('layouts.admin')
@section('title', 'Edit Promotion')

@section('breadcrumbs', [
    ['label' => 'Promotions', 'url' => route('admin.promotions.index')],
    ['label' => 'Edit']
])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-form">
            <form method="POST" action="{{ $action }}">
                @csrf
                @method('PUT')
                
                <h4 class="mb-4">Promotion Information</h4>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Promotion Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control-admin" 
                               value="{{ old('code', $promotion->code ?? '') }}" 
                               required style="text-transform: uppercase;">
                        @error('code')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control-admin" 
                               value="{{ old('title', $promotion->title ?? '') }}" 
                               required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-select-admin" required>
                            <option value="percent" @selected(old('discount_type', $promotion->discount_type ?? '') == 'percent')>Percentage (%)</option>
                            <option value="fixed" @selected(old('discount_type', $promotion->discount_type ?? '') == 'fixed')>Fixed Amount (VND)</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                        <input type="number" name="discount_value" class="form-control-admin" 
                               value="{{ old('discount_value', $promotion->discount_value ?? '') }}" 
                               min="0" required>
                        @error('discount_value')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_at" class="form-control-admin" 
                               value="{{ old('start_at', $promotion->start_at ? \Carbon\Carbon::parse($promotion->start_at)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_at" class="form-control-admin" 
                               value="{{ old('end_at', $promotion->end_at ? \Carbon\Carbon::parse($promotion->end_at)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select-admin">
                            <option value="1" @selected(old('is_active', $promotion->is_active ?? true) == 1)>Active</option>
                            <option value="0" @selected(old('is_active', $promotion->is_active ?? true) == 0)>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control-admin" rows="3">{{ old('description', $promotion->description ?? '') }}</textarea>
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn-admin btn-admin-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <h5 class="admin-card-title mb-3">Quick Info</h5>
            <div class="detail-section">
                <div class="detail-item">
                    <span class="detail-label">Created</span>
                    <span class="detail-value">{{ $promotion->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Times Used</span>
                    <span class="detail-value">{{ $promotion->usage_count ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title mb-3">Warning</h5>
            <p class="text-muted" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle me-2" style="color: var(--admin-warning);"></i>
                Editing an active promotion may affect customers who are already using it.
            </p>
        </div>
    </div>
</div>
@endsection
