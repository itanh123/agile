<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship: Permission Group có nhiều Permissions
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'group_id');
    }

    /**
     * Scope: chỉ lấy permission groups active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get sorted permissions theo sort_order
     */
    public function getSortedPermissionsAttribute()
    {
        return $this->permissions()->orderBy('sort_order')->get();
    }
}
