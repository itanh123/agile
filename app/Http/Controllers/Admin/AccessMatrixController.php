<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;

class AccessMatrixController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $modules = Permission::where('is_active', true)
            ->where('module', '!=', '')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $permissions = Permission::where('is_active', true)
            ->orderBy('module')
            ->orderBy('name')
            ->get();

        $matrix = [];
        foreach ($roles as $role) {
            $rolePerms = $role->permissions->pluck('name')->toArray();
            foreach ($permissions as $permission) {
                $matrix[$role->id][$permission->id] = in_array($permission->name, $rolePerms);
            }
        }

        return view('admin.access-matrix.index', compact(
            'roles',
            'permissions',
            'modules',
            'matrix'
        ));
    }
}
