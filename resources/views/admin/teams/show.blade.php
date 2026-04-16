@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <a href="{{ route('admin.teams.index') }}">Teams</a>
        <span>/</span>
        <span>{{ $user->fullname ?? $user->name }}</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-people me-2 text-primary"></i>Team: {{ $user->fullname ?? $user->name }}
            </h3>
            <p class="text-muted mb-0">Danh sách thành viên trong team.</p>
        </div>
        <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Leader</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="user-avatar-lg mx-auto">
                            {{ strtoupper(substr($user->fullname ?? $user->name, 0, 1)) }}
                        </div>
                        <h5 class="mt-2 mb-1">{{ $user->fullname ?? $user->name }}</h5>
                        <span class="badge bg-primary">{{ $user->role?->name ?? 'Staff' }}</span>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="text-muted small d-block">Email</label>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small d-block">Số thành viên</label>
                        <span class="badge bg-info">{{ $teamMembers->count() }}</span>
                    </div>
                    <div>
                        <label class="text-muted small d-block">Quyền</label>
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            @foreach($user->role?->permissions->take(5) as $perm)
                                <span class="badge bg-secondary">{{ $perm->name }}</span>
                            @endforeach
                            @if($user->role?->permissions->count() > 5)
                                <span class="badge bg-light text-dark">+{{ $user->role?->permissions->count() - 5 }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Team Members
                        <span class="badge bg-primary ms-2">{{ $teamMembers->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($teamMembers->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-0">Chưa có thành viên nào trong team.</p>
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
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teamMembers as $member)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar-sm">
                                                        {{ strtoupper(substr($member->fullname ?? $member->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <span class="fw-medium">{{ $member->fullname ?? $member->name }}</span>
                                                        <br><small class="text-muted">{{ $member->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($member->role)
                                                    <span class="badge bg-primary">{{ $member->role->name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse($member->directPermissions->take(2) as $perm)
                                                    <span class="badge bg-success">{{ $perm->name }}</span>
                                                @empty
                                                    <span class="text-muted">-</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $member->bookings()->count() }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.permissions.edit', $member) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-key"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .user-avatar-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #18bc9c;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 28px;
    }
    
    .user-avatar-sm {
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
