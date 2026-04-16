<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role', 'directPermissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        $users = $query->latest()->paginate(20);
        $roles = Role::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.permissions-index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $modules = Permission::where('is_active', true)
            ->where('module', '!=', '')
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        $directPermissionIds = $user->directPermissions()->pluck('permissions.id')->toArray();
        $rolePermissionIds = $user->role ? $user->role->permissions()->pluck('permissions.id')->toArray() : [];

        return view('admin.users.permissions-edit', compact(
            'user',
            'modules',
            'directPermissionIds',
            'rolePermissionIds'
        ));
    }

    public function update(Request $request, User $user)
    {
        $permissionIds = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->directPermissions()->sync($permissionIds['permissions'] ?? []);

        return redirect()->route('admin.users.permissions.index')->with('success', 'Phân quyền cho người dùng đã được cập nhật.');
    }

    public function updateManager(Request $request, User $user)
    {
        $request->validate([
            'manager_id' => 'nullable|exists:users,id',
        ]);

        if ($request->manager_id == $user->id) {
            return back()->with('error', 'Người dùng không thể tự quản lý chính mình.');
        }

        $user->update(['manager_id' => $request->manager_id]);

        return back()->with('success', 'Quản lý đã được cập nhật.');
    }
}
