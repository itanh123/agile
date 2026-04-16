<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        $permissions = $query->withCount('roles', 'users')->latest()->paginate(20);
        
        $modules = Permission::where('module', '!=', '')
            ->where('is_active', true)
            ->distinct()
            ->pluck('module');

        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    public function create()
    {
        $modules = Permission::where('is_active', true)
            ->where('module', '!=', '')
            ->distinct()
            ->pluck('module');
            
        return view('admin.permissions.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:permissions,name',
            'module' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên quyền là bắt buộc.',
            'name.unique' => 'Tên quyền đã tồn tại.',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = true;

        Permission::create($data);

        return redirect()->route('admin.permissions.index')->with('success', 'Quyền đã được tạo thành công.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50|unique:permissions,name,' . $permission->id,
            'module' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->has('is_active');

        $permission->update($data);

        return redirect()->route('admin.permissions.index')->with('success', 'Quyền đã được cập nhật thành công.');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Không thể xóa quyền này vì đang được gán cho vai trò.');
        }

        if ($permission->users()->count() > 0) {
            return back()->with('error', 'Không thể xóa quyền này vì đang được gán cho người dùng.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Quyền đã được xóa thành công.');
    }
}
