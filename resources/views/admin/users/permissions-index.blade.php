@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Phân Quyền Người Dùng</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-people me-2 text-primary"></i>Phân Quyền Người Dùng
            </h3>
            <p class="text-muted mb-0">Quản lý quyền trực tiếp cho từng người dùng.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm theo tên, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">-- Tất cả vai trò --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(request('role') == $role->id)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Tìm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-outline-secondary w-100">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Người Dùng</th>
                            <th>Email</th>
                            <th>Vai Trò</th>
                            <th>Quyền Trực Tiếp</th>
                            <th>Quản Lý</th>
                            <th class="text-end">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar">
                                            {{ strtoupper(substr($user->fullname ?? $user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-medium">{{ $user->fullname ?? $user->name }}</span>
                                            @if($user->manager)
                                                <br><small class="text-muted">Leader: {{ $user->manager->fullname ?? $user->manager->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role)
                                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($user->directPermissions->take(3) as $perm)
                                        <span class="badge bg-success mb-1">{{ $perm->name }}</span>
                                    @empty
                                        <span class="text-muted small">Không có</span>
                                    @endforelse
                                    @if($user->directPermissions->count() > 3)
                                        <br><span class="text-muted small">+{{ $user->directPermissions->count() - 3 }} quyền khác</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->subordinates->count() > 0)
                                        <span class="badge bg-info">{{ $user->subordinates->count() }} thành viên</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.users.permissions.edit', $user) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Phân quyền">
                                            <i class="bi bi-key"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="showManagerModal({{ $user->id }}, '{{ $user->fullname ?? $user->name }}', {{ $user->manager_id }})"
                                                    title="Gán leader">
                                                <i class="bi bi-person-badge"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-people" style="font-size: 48px;"></i>
                                    <p class="mt-2 mb-0">Không có người dùng nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Manager Modal -->
    <div class="modal fade" id="managerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gán Quản Lý</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="managerForm">
                    @csrf @method('PATCH')
                    <div class="modal-body">
                        <p id="managerUserName"></p>
                        <div class="mb-3">
                            <label class="form-label">Chọn Quản Lý / Leader</label>
                            <select name="manager_id" class="form-select" id="managerSelect">
                                <option value="">-- Không có --</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #3498db;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
</style>
@endpush

@push('scripts')
<script>
    let managers = @json(\App\Models\User::with('role')->get()->map(function($u) {
        return ['id' => $u->id, 'name' => $u->fullname ?? $u->name, 'role' => $u->role?->name];
    }));

    function showManagerModal(userId, userName, currentManagerId) {
        document.getElementById('managerUserName').textContent = 'Gán quản lý cho: ' + userName;
        document.getElementById('managerForm').action = '/admin/user-permissions/' + userId + '/manager';
        
        const select = document.getElementById('managerSelect');
        select.innerHTML = '<option value="">-- Không có --</option>';
        
        managers.forEach(m => {
            if (m.id !== userId) {
                const option = document.createElement('option');
                option.value = m.id;
                option.textContent = m.name + ' (' + m.role + ')';
                option.selected = m.id == currentManagerId;
                select.appendChild(option);
            }
        });
        
        new bootstrap.Modal(document.getElementById('managerModal')).show();
    }
</script>
@endpush
