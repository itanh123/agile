@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.users.permissions.index') }}">Phân Quyền Người Dùng</a>
        <span>/</span>
        <span>{{ $user->fullname ?? $user->name }}</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-person-check me-2 text-primary"></i>Phân Quyền Cho: 
                <span class="text-primary">{{ $user->fullname ?? $user->name }}</span>
            </h3>
            <p class="text-muted mb-0">Gán quyền trực tiếp cho người dùng (bổ sung cho quyền từ vai trò).</p>
        </div>
        <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông Tin Người Dùng</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="user-avatar-lg mx-auto">
                            {{ strtoupper(substr($user->fullname ?? $user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Họ Tên</label>
                        <span class="fw-medium">{{ $user->fullname ?? $user->name }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Email</label>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Vai Trò</label>
                        @if($user->role)
                            <span class="badge bg-primary fs-6">{{ $user->role->name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    @if($user->manager)
                    <div class="mb-3">
                        <label class="text-muted small d-block">Quản Lý</label>
                        <span>{{ $user->manager->fullname ?? $user->manager->name }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quyền Từ Vai Trò</h5>
                </div>
                <div class="card-body">
                    @if($user->role && $user->role->permissions->count() > 0)
                        <p class="text-muted small mb-2">Những quyền này được kế thừa từ vai trò <strong>{{ $user->role->name }}</strong> và không thể thay đổi ở đây.</p>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($user->role->permissions as $perm)
                                <span class="badge bg-primary">{{ $perm->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Không có quyền từ vai trò.</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Giải Thích</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Quyền từ vai trò được áp dụng tự động.</li>
                        <li class="mb-2">Quyền trực tiếp là bổ sung thêm cho người dùng.</li>
                        <li>Quyền trực tiếp có ưu tiên cao hơn quyền từ vai trò.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}">
                @csrf @method('PATCH')

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Quyền Trực Tiếp</h5>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="checkAll" onclick="toggleAll(this)">
                            <label class="form-check-label" for="checkAll">
                                <strong>Chọn Tất Cả</strong>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($modules as $moduleName => $modulePermissions)
                            <div class="card mb-3 border">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                    <div class="form-check mb-0">
                                        <input type="checkbox" class="form-check-input module-check" 
                                               id="module_{{ $moduleName }}" 
                                               onclick="toggleModule('{{ $moduleName }}')">
                                        <label class="form-check-label fw-bold" for="module_{{ $moduleName }}">
                                            {{ ucfirst($moduleName) }}
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row">
                                        @foreach($modulePermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           class="form-check-input permission-check module-{{ $moduleName }}"
                                                           id="perm_{{ $permission->id }}"
                                                           @checked(in_array($permission->id, $directPermissionIds))>
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                        <code>{{ $permission->name }}</code>
                                                        @if(in_array($permission->id, $rolePermissionIds))
                                                            <span class="badge bg-primary ms-1">từ vai trò</span>
                                                        @endif
                                                        @if($permission->description)
                                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-shield-slash" style="font-size: 48px;"></i>
                                <p class="mt-2 mb-0">Chưa có quyền nào.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if(!$modules->isEmpty())
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-2"></i>Lưu Phân Quyền
                    </button>
                    <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-outline-secondary">
                        Hủy
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .user-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #3498db;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 28px;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleModule(moduleName) {
        const checkboxes = document.querySelectorAll('.module-' + moduleName);
        const moduleCheckbox = document.getElementById('module_' + moduleName);
        
        checkboxes.forEach(cb => cb.checked = moduleCheckbox.checked);
    }

    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('.permission-check');
        checkboxes.forEach(cb => cb.checked = source.checked);
        
        const moduleCheckboxes = document.querySelectorAll('.module-check');
        moduleCheckboxes.forEach(cb => cb.checked = source.checked);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modules = document.querySelectorAll('.module-check');
        modules.forEach(moduleCb => {
            const moduleName = moduleCb.id.replace('module_', '');
            const permCheckboxes = document.querySelectorAll('.module-' + moduleName);
            const allChecked = Array.from(permCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(permCheckboxes).some(cb => cb.checked);
            
            moduleCb.checked = allChecked;
            moduleCb.indeterminate = someChecked && !allChecked;
        });
    });

    document.querySelectorAll('.permission-check').forEach(cb => {
        cb.addEventListener('change', function() {
            const moduleName = this.className.match(/module-(\w+)/)?.[1];
            if (!moduleName) return;
            
            const moduleCheckbox = document.getElementById('module_' + moduleName);
            const permCheckboxes = document.querySelectorAll('.module-' + moduleName);
            const allChecked = Array.from(permCheckboxes).every(c => c.checked);
            const someChecked = Array.from(permCheckboxes).some(c => c.checked);
            
            moduleCheckbox.checked = allChecked;
            moduleCheckbox.indeterminate = someChecked && !allChecked;
        });
    });
</script>
@endpush
