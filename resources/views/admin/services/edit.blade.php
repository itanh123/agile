@extends('layouts.admin')
@section('title', 'Edit Service')

@section('breadcrumbs', [
    ['label' => 'Services', 'url' => route('admin.services.index')],
    ['label' => 'Edit']
])

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-form">
            <form method="POST" action="{{ $action }}">
                @csrf
                @method('PUT')
                
                <h4 class="mb-4">Service Information</h4>
                
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Service Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control-admin" 
                               value="{{ old('name', $service->name ?? '') }}" 
                               placeholder="e.g., Premium Grooming Package" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Service Type <span class="text-danger">*</span></label>
                        <select name="service_type" class="form-select-admin" required>
                            <option value="">Select Type</option>
                            <option value="grooming" @selected(old('service_type', $service->service_type ?? '') == 'grooming')>Grooming</option>
                            <option value="vaccination" @selected(old('service_type', $service->service_type ?? '') == 'vaccination')>Vaccination</option>
                            <option value="spa" @selected(old('service_type', $service->service_type ?? '') == 'spa')>Spa</option>
                            <option value="checkup" @selected(old('service_type', $service->service_type ?? '') == 'checkup')>Checkup</option>
                            <option value="surgery" @selected(old('service_type', $service->service_type ?? '') == 'surgery')>Surgery</option>
                        </select>
                        @error('service_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Price (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control-admin" 
                               value="{{ old('price', $service->price ?? '') }}" 
                               placeholder="e.g., 150000" min="0" required>
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Duration (minutes) <span class="text-danger">*</span></label>
                        <input type="number" name="duration_minutes" class="form-control-admin" 
                               value="{{ old('duration_minutes', $service->duration_minutes ?? 30) }}" 
                               placeholder="e.g., 30" min="15" required>
                        @error('duration_minutes')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select-admin">
                            <option value="1" @selected(old('is_active', $service->is_active ?? true) == 1)>Active</option>
                            <option value="0" @selected(old('is_active', $service->is_active ?? true) == 0)>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control-admin" rows="4" 
                                  placeholder="Describe the service in detail..." required>{{ old('description', $service->description ?? '') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn-admin btn-admin-secondary">
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
                    <span class="detail-value">{{ $service->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">{{ $service->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Total Bookings</span>
                    <span class="detail-value">{{ $service->total_bookings ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="admin-card">
            <h5 class="admin-card-title mb-3">Warning</h5>
            <p class="text-muted" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle me-2" style="color: var(--admin-warning);"></i>
                Changing the service details may affect existing bookings.
            </p>
        </div>
    </div>
</div>
@endsection
