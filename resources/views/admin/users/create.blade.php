@extends('layouts.admin')
@section('title', 'Create User')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<a href="{{ route('admin.users.index') }}">Users</a>
<i class="bi bi-chevron-right"></i>
<span>Create</span>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="admin-form">
            <form method="POST" action="{{ route('admin.users.store') }}" novalidate>
                @csrf
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control-admin" 
                               value="{{ old('full_name', $user->full_name ?? '') }}" 
                               placeholder="Enter full name" required>
                        @error('full_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control-admin" 
                               value="{{ old('email', $user->email ?? '') }}" 
                               placeholder="Enter email address" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control-admin" 
                               value="{{ old('phone', $user->phone ?? '') }}" 
                               placeholder="Enter phone number">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control-admin" 
                               value="{{ old('address', $user->address ?? '') }}" 
                               placeholder="Enter address">
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select name="role_id" class="form-select-admin" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ isset($user) ? 'New Password' : 'Password' }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control-admin" 
                               placeholder="{{ isset($user) ? 'Leave blank to keep current' : 'Enter password' }}"
                               {{ isset($user) ? '' : 'required' }}>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    @if(isset($user))
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" id="isActive" 
                                   class="form-check-input" @checked(old('is_active', $user->is_active ?? true))>
                            <label class="form-check-label" for="isActive">
                                Active Account
                            </label>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-admin btn-admin-primary">
                        <i class="bi bi-check-lg"></i> Save User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-secondary">
                        <i class="bi bi-x-lg"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="admin-card">
            <h5 class="admin-card-title mb-3">Role Permissions</h5>
            <div class="detail-section">
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-person me-2"></i>Customer</span>
                    <span class="detail-value">Book services, manage profile</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-person-badge me-2"></i>Staff</span>
                    <span class="detail-value">Handle bookings, update status</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><i class="bi bi-shield me-2"></i>Admin</span>
                    <span class="detail-value">Full system access</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
