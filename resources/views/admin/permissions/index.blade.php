@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Quyền</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-key me-2 text-primary"></i>Quản Lý Quyền
            </h3>
            <p class="text-muted mb-0">Tạo và quản lý các quyền trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Thêm Quyền Mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm quyền..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="module" class="form-select">
                        <option value="">-- Tất cả modules --</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" @selected(request('module') == $module)>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Tìm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Quyền</th>
                            <th>Module</th>
                            <th>Mô Tả</th>
                            <th class="text-center">Vai Trò</th>
                            <th class="text-center">Người Dùng</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="text-end">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                            <tr>
                                <td>
                                    <code class="fw-bold">{{ $permission->name }}</code>
                                </td>
                                <td>
                                    @if($permission->module)
                                        <span class="badge bg-primary">{{ ucfirst($permission->module) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($permission->description, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $permission->roles_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $permission->users_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    @if($permission->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.permissions.edit', $permission) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" 
                                              class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                    @if(($permission->roles_count ?? 0) > 0 || ($permission->users_count ?? 0) > 0) disabled @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-shield-slash" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">Chưa có quyền nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $permissions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
