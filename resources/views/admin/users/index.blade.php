@extends($baseLayout)
@section('title', 'Users Management')

@push('breadcrumbs')
<i class="bi bi-chevron-right"></i>
<span>Users</span>
@endpush

@section('content')
<div class="page-header">
    <h2>Users Management</h2>
    <a href="{{ route('admin.users.create') }}" class="btn-admin btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Add User
    </a>
</div>

<!-- Filters -->
<div class="admin-card mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
        <div class="form-group">
                <div class="search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" class="form-control-admin" placeholder="Search by name or email..." value="{{ request('q') }}">
                </div>
        </div>
        <div class="form-group">
            <select name="role" class="form-select-admin">
                <option value="">All Roles</option>
                @foreach($roles ?? [] as $role)
                    <option value="{{ $role->id }}" @selected(request('role') == $role->id)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-admin btn-admin-secondary">
            <i class="bi bi-funnel"></i> Filter
        </button>
        @if(request()->has('q') || request()->has('role'))
            <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-secondary">
                <i class="bi bi-x-lg"></i> Clear
            </a>
        @endif
    </form>
</div>

<!-- Users Table -->
<div class="admin-card">
    @if($users->isEmpty())
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <h4>No users found</h4>
            <p>Try adjusting your search criteria.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="table-avatar">
                                        {{ strtoupper(substr($user->full_name ?? $user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->full_name ?? $user->name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge-role" style="background: rgba(99, 102, 241, 0.15); color: var(--admin-primary); padding: 0.25rem 0.625rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600;">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($user->is_active))
                                    <span class="status-badge {{ $user->is_active ? 'active' : 'inactive' }}">
                                        <i class="bi bi-{{ $user->is_active ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @else
                                    <span class="status-badge active">
                                        <i class="bi bi-check-circle"></i> Active
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn-admin btn-admin-secondary btn-admin-sm btn-admin-icon" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($user->role?->slug !== 'admin')
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin btn-admin-secondary btn-admin-sm btn-admin-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm btn-admin-icon" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <p class="text-muted mb-0" style="font-size: 0.85rem;">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
            </p>
            {{ $users->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
