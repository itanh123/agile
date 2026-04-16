@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.roles.index') }}">Vai trò</a>
        <span>/</span>
        <span>{{ $role->name }}</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-pencil me-2 text-warning"></i>Chỉnh Sửa Vai Trò
            </h3>
            <p class="text-muted mb-0">Cập nhật thông tin vai trò: <strong>{{ $role->name }}</strong></p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tên Vai Trò <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $role->name) }}" placeholder="VD: Editor">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" 
                                   value="{{ old('slug', $role->slug) }}" placeholder="VD: editor">
                            <small class="text-muted">Slug dùng để nhận dạng vai trò trong code.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả ngắn về vai trò này...">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                       value="1" @checked($role->is_active)>
                                <label class="form-check-label" for="is_active">
                                    Vai trò đang hoạt động
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Cập Nhật
                            </button>
                            <a href="{{ route('admin.roles.permissions', $role) }}" class="btn btn-info text-white">
                                <i class="bi bi-key me-2"></i>Phân Quyền
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông Tin</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Người dùng:</strong>
                        <span class="badge bg-info ms-2">{{ $role->users_count ?? $role->users()->count() }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Quyền được gán:</strong>
                        <span class="badge bg-primary ms-2">{{ $role->permissions->count() }}</span>
                    </div>
                    <div>
                        <strong>Ngày tạo:</strong>
                        <span class="text-muted ms-2">{{ $role->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quyền Hiện Tại</h5>
                </div>
                <div class="card-body">
                    @if($role->permissions->isEmpty())
                        <p class="text-muted mb-0">Chưa có quyền nào được gán.</p>
                    @else
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($role->permissions as $permission)
                                <span class="badge bg-secondary">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
