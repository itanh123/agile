@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.permissions.index') }}">Quyền</a>
        <span>/</span>
        <span>{{ $permission->name }}</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-pencil me-2 text-warning"></i>Chỉnh Sửa Quyền
            </h3>
            <p class="text-muted mb-0">Cập nhật thông tin quyền: <strong>{{ $permission->name }}</strong></p>
        </div>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.permissions.update', $permission) }}">
                        @csrf @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Tên Quyền <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $permission->name) }}" placeholder="VD: booking.update_status">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Module</label>
                            <input type="text" name="module" class="form-control @error('module') is-invalid @enderror" 
                                   value="{{ old('module', $permission->module) }}" placeholder="VD: booking">
                            @error('module')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Mô tả ngắn về quyền này...">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                                       value="1" @checked($permission->is_active)>
                                <label class="form-check-label" for="is_active">
                                    Quyền đang hoạt động
                                </label>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Cập Nhật
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
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông Tin</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Slug:</strong>
                        <code class="ms-2">{{ $permission->slug }}</code>
                    </div>
                    <div class="mb-2">
                        <strong>Vai trò sử dụng:</strong>
                        <span class="badge bg-info ms-2">{{ $permission->roles_count ?? $permission->roles()->count() }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Người dùng sử dụng:</strong>
                        <span class="badge bg-secondary ms-2">{{ $permission->users_count ?? $permission->users()->count() }}</span>
                    </div>
                    <div>
                        <strong>Ngày tạo:</strong>
                        <span class="text-muted ms-2">{{ $permission->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Vai Trò Có Quyền Này</h5>
                </div>
                <div class="card-body">
                    @if($permission->roles->isEmpty())
                        <p class="text-muted mb-0">Không có vai trò nào có quyền này.</p>
                    @else
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($permission->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
