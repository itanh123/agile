<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Relationship: Service Category có nhiều Services
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope: chỉ lấy active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get active services trong category này
     */
    public function getActiveServicesAttribute()
    {
        return $this->services()->active()->get();
    }
}
