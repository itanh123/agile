@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Vai trò</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-shield-check me-2 text-primary"></i>Quản Lý Vai Trò
            </h3>
            <p class="text-muted mb-0">Tạo và quản lý các vai trò trong hệ thống.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Thêm Vai Trò Mới
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm vai trò..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Tìm
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vai Trò</th>
                            <th>Slug</th>
                            <th>Mô Tả</th>
                            <th class="text-center">Số Người Dùng</th>
                            <th class="text-center">Số Quyền</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="text-end">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $role->name }}</span>
                                </td>
                                <td>
                                    <code>{{ $role->slug }}</code>
                                </td>
                                <td>{{ Str::limit($role->description, 50) ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $role->users_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $role->permissions->count() }}</span>
                                </td>
                                <td class="text-center">
                                    @if($role->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.roles.permissions', $role) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Phân quyền">
                                            <i class="bi bi-key"></i>
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" 
                                              class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa"
                                                    @if(($role->users_count ?? 0) > 0) disabled @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-shield" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">Chưa có vai trò nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $roles->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
