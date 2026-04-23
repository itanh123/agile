<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'module',
        'resource_type',
        'action',
        'group_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Permission thuộc về nhiều Roles (pivot)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Relationship: Permission thuộc về nhiều Users (pivot)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Relationship: Permission thuộc về PermissionGroup
     */
    public function group()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }

    /**
     * Scope: chỉ lấy permissions active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: lọc theo resource type
     */
    public function scopeForResource($query, string $resourceType)
    {
        return $query->where('resource_type', $resourceType);
    }

    /**
     * Scope: lọc theo action
     */
    public function scopeWithAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: lọc theo group
     */
    public function scopeInGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    /**
     * Get full permission key (resource.action)
     */
    public function getKeyAttribute()
    {
        return $this->resource_type . '.' . $this->action;
    }

    /**
     * Check if permission matches resource and action
     */
    public function matches(string $resourceType, string $action): bool
    {
        return $this->resource_type === $resourceType && $this->action === $action;
    }

    /**
     * Get formatted name with resource
     */
    public function getFullNameAttribute()
    {
        $resourceLabels = [
            'booking' => 'Đặt lịch',
            'pet' => 'Thú cưng',
            'service' => 'Dịch vụ',
            'payment' => 'Thanh toán',
            'user' => 'Người dùng',
            'promotion' => 'Khuyến mãi',
            'report' => 'Báo cáo',
            'setting' => 'Cài đặt',
        ];

        $resource = $resourceLabels[$this->resource_type] ?? $this->resource_type;
        $actionLabels = [
            'view' => 'xem',
            'create' => 'tạo',
            'update' => 'sửa',
            'delete' => 'xóa',
            'approve' => 'duyệt',
            'assign' => 'phân công',
            'manage' => 'quản lý',
        ];

        $action = $actionLabels[$this->action] ?? $this->action;

        return "{$resource} - {$action}";
    }
}
