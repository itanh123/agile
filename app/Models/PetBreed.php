<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetBreed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'is_active',
        'deleted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: PetBreed thuộc về PetCategory
     */
    public function category()
    {
        return $this->belongsTo(PetCategory::class);
    }

    /**
     * Relationship: PetBreed có nhiều Pets
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Scope: chỉ lấy breeds active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('deleted_at');
    }

    /**
     * Get total pets of this breed
     */
    public function getTotalPetsAttribute()
    {
        return $this->pets()->count();
    }
}
