@extends('layouts.admin')
@section('content')
    @push('breadcrumbs')
        <span>/</span>
        <span>Access Matrix</span>
    @endpush

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-grid-3x3 me-2 text-primary"></i>Access Matrix
            </h3>
            <p class="text-muted mb-0">Bảng ma trận phân quyền theo vai trò.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($permissions->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-shield-slash" style="font-size: 64px; color: #dee2e6;"></i>
                    <h5 class="mt-3 text-muted">Chưa có quyền nào</h5>
                    <p class="text-muted">Hãy tạo quyền trước.</p>
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-2"></i>Tạo Quyền
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 250px;">Permission</th>
                                @foreach($roles as $role)
                                    <th class="text-center" style="width: 120px;">
                                        {{ $role->name }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentModule = '';
                            @endphp
                            @foreach($permissions as $permission)
                                @if($permission->module !== $currentModule)
                                    @php
                                        $currentModule = $permission->module;
                                        $modulePermissions = $permissions->where('module', $currentModule);
                                    @endphp
                                    <tr class="table-secondary">
                                        <td colspan="{{ $roles->count() + 1 }}" class="fw-bold">
                                            <i class="bi bi-folder me-2"></i>{{ ucfirst($currentModule ?: 'Other') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <code>{{ $permission->name }}</code>
                                        @if($permission->description)
                                            <br><small class="text-muted">{{ Str::limit($permission->description, 40) }}</small>
                                        @endif
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="text-center">
                                            @if(isset($matrix[$role->id][$permission->id]) && $matrix[$role->id][$permission->id])
                                                <i class="bi bi-check-circle-fill text-success fs-5" title="Có quyền"></i>
                                            @else
                                                <i class="bi bi-x-circle text-secondary fs-5" title="Không có quyền"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Legend</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <span>Có quyền</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-x-circle text-secondary fs-5"></i>
                            <span>Không có quyền</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-shield me-1"></i>Quản lý Vai trò
                        </a>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-key me-1"></i>Quản lý Quyền
                        </a>
                        <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-people me-1"></i>Phân Quyền User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
