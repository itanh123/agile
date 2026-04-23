<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'is_active'];

    /**
     * Relationship: Role có nhiều Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relationship: Role có nhiều Permissions (pivot)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Scope: chỉ lấy roles active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check permission by slug (legacy)
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check permission by resource + action (new system)
     */
    public function hasPermissionTo(string $resourceType, string $action): bool
    {
        return $this->permissions()
            ->where(function($q) use ($resourceType, $action) {
                $q->where(function($qq) use ($resourceType, $action) {
                    $qq->where('resource_type', $resourceType)
                       ->where('action', $action);
                })->orWhere('slug', "{$resourceType}.{$action}");
            })
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get all permissions as flat collection
     */
    public function getAllPermissionsAttribute()
    {
        return $this->permissions;
    }
}
