<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'full_name',
        'phone',
        'address',
        'role_id',
        'manager_id',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
        'avatar',
        'date_of_birth',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    /**
     * Relationship: User belongs to Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: User có nhiều Pets
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Relationship: User có nhiều Bookings (作为customer)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relationship: User có nhiều Bookings (作为staff)
     */
    public function staffBookings()
    {
        return $this->hasMany(Booking::class, 'staff_id');
    }

    /**
     * Relationship: User có nhiều Payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relationship: User có nhiều PromotionUses
     */
    public function promotionUses()
    {
        return $this->hasMany(PromotionUse::class);
    }

    /**
     * Relationship: Permissions trực tiếp của user
     */
    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Relationship: Manager của user
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Relationship: Subordinates của user
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Relationship: Audit logs của user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get tất cả permissions của user (role + direct)
     */
    public function getAllPermissionsAttribute()
    {
        $rolePermissions = $this->role?->permissions()?->pluck('id')?->toArray() ?? [];
        $directPermissions = $this->directPermissions?->pluck('id')?->toArray() ?? [];

        return array_unique(array_merge($rolePermissions, $directPermissions));
    }

    /**
     * Check permission by slug (unified method)
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Admin bypass
        if ($this->role?->slug === 'admin') {
            return true;
        }

        // Nếu chuỗi chứa dấu chấm, thử check theo có resource.action
        if (str_contains($permissionSlug, '.')) {
            [$resource, $action] = explode('.', $permissionSlug);
            return $this->hasPermissionTo($resource, $action);
        }

        // Check direct permissions
        if ($this->directPermissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        // Check role permissions
        if ($this->role && $this->role->permissions()->where('slug', $permissionSlug)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Check permission by resource + action (new system)
     */
    public function hasPermissionTo(string $resourceType, string $action, $resourceId = null): bool
    {
        // Admin bypass
        if ($this->role?->slug === 'admin') {
            return true;
        }

        // Query tìm permission record
        $permissionQuery = Permission::where(function($q) use ($resourceType, $action) {
            $q->where(function($qq) use ($resourceType, $action) {
                $qq->where('resource_type', $resourceType)
                   ->where('action', $action);
            })->orWhere('slug', "{$resourceType}.{$action}");
        })->where('is_active', true);

        $permission = $permissionQuery->first();

        if (!$permission) {
            return false;
        }

        // Check direct permission
        if ($this->directPermissions()->where('permission_id', $permission->id)->exists()) {
            return true;
        }

        // Check role permission
        if ($this->role && $this->role->permissions()->where('permission_id', $permission->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Check multiple permissions (OR)
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check multiple permissions (AND)
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
}
