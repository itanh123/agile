@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.permissions.index') }}">Quyền</a>
        <span>/</span>
        <span>Tạo mới</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Tạo Quyền Mới
            </h3>
            <p class="text-muted mb-0">Thêm một quyền mới vào hệ thống.</p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.permissions.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Tên Quyền <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" placeholder="VD: booking.update_status">
                            <small class="text-muted">Sử dụng format: module.action (ví dụ: booking.view)</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Module</label>
                            <input type="text" name="module" class="form-control @error('module') is-invalid @enderror" 
                                   value="{{ old('module') }}" placeholder="VD: booking">
                            <small class="text-muted">Module để nhóm các quyền liên quan.</small>
                            @error('module')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả ngắn về quyền này...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Lưu Quyền
                            </button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Modules Hiện Có</h5>
                </div>
                <div class="card-body">
                    @forelse($modules as $module)
                        <span class="badge bg-secondary mb-1">{{ ucfirst($module) }}</span>
                    @empty
                        <p class="text-muted mb-0">Chưa có module nào.</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Hướng Dẫn</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 small">
                        <li class="mb-2">Tên quyền nên theo format: <code>module.action</code></li>
                        <li class="mb-2">Ví dụ: <code>booking.update_status</code></li>
                        <li class="mb-2">Module tự động trích xuất từ tên quyền.</li>
                        <li>Slug sẽ tự động tạo từ tên.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
