@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Teams</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-diagram-3 me-2 text-primary"></i>Quản Lý Teams
            </h3>
            <p class="text-muted mb-0">Xem cấu trúc team và quản lý leader - thành viên.</p>
        </div>
    </div>

    @forelse($teams as $team)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="leader-avatar">
                            {{ strtoupper(substr($team['leader']->fullname ?? $team['leader']->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $team['leader']->fullname ?? $team['leader']->name }}</h5>
                            <span class="badge bg-primary">{{ $team['leader']->role?->name ?? 'Staff' }}</span>
                            @if($team['leader']->email)
                                <small class="text-muted ms-2">{{ $team['leader']->email }}</small>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-info me-2">{{ $team['members']->count() }} thành viên</span>
                        <a href="{{ route('admin.teams.show', $team['leader']) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Xem Team
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                @if($team['members']->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <p class="mb-0">Chưa có thành viên nào.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thành Viên</th>
                                    <th>Vai Trò</th>
                                    <th>Quyền Trực Tiếp</th>
                                    <th>Bookings</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team['members'] as $member)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="member-avatar">
                                                    {{ strtoupper(substr($member->fullname ?? $member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-medium">{{ $member->fullname ?? $member->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($member->role)
                                                <span class="badge bg-secondary">{{ $member->role->name }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse($member->directPermissions->take(3) as $perm)
                                                <span class="badge bg-success mb-1">{{ $perm->name }}</span>
                                            @empty
                                                <span class="text-muted small">Không có</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $member->bookings()->count() }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.users.permissions.edit', $member) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Phân quyền">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-people" style="font-size: 64px; color: #dee2e6;"></i>
                <h5 class="mt-3 text-muted">Chưa có Team nào</h5>
                <p class="text-muted mb-0">Teams được tạo khi có người dùng được gán làm quản lý của người khác.</p>
            </div>
        </div>
    @endforelse
@endsection

@push('styles')
<style>
    .leader-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #18bc9c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }
    
    .member-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #3498db;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 12px;
    }
</style>
@endpush
