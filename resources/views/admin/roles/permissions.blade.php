@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.roles.index') }}">Vai trò</a>
        <span>/</span>
        <a href="{{ route('admin.roles.edit', $role) }}">{{ $role->name }}</a>
        <span>/</span>
        <span>Phân quyền</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-key me-2 text-primary"></i>Phân Quyền Cho: <span class="text-primary">{{ $role->name }}</span>
            </h3>
            <p class="text-muted mb-0">Gán các quyền cho vai trò này.</p>
        </div>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
        @csrf @method('PATCH')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Danh Sách Quyền</h5>
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
                                                   @checked(in_array($permission->id, $rolePermissions))>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                <code>{{ $permission->name }}</code>
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
                        <p class="mt-2 mb-0">Chưa có quyền nào. Hãy tạo quyền trước.</p>
                        <a href="{{ route('admin.permissions.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-plus-lg me-2"></i>Tạo Quyền Mới
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if(!$modules->isEmpty())
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-2"></i>Lưu Phân Quyền
            </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
                Hủy
            </a>
        </div>
        @endif
    </form>
@endsection

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
