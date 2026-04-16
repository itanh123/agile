<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $roles = $query->withCount('users')->latest()->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'slug' => 'nullable|string|max:50|unique:roles,slug',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên vai trò là bắt buộc.',
            'name.unique' => 'Tên vai trò đã tồn tại.',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = true;

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Vai trò đã được tạo thành công.');
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'slug' => 'nullable|string|max:50|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên vai trò là bắt buộc.',
            'name.unique' => 'Tên vai trò đã tồn tại.',
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->has('is_active');

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('success', 'Vai trò đã được cập nhật thành công.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Không thể xóa vai trò này vì đang có người dùng sử dụng.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Vai trò đã được xóa thành công.');
    }

    public function permissions(Role $role)
    {
        $modules = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');

        $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

        return view('admin.roles.permissions', compact('role', 'modules', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($permissionIds['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Phân quyền cho vai trò đã được cập nhật.');
    }
}
