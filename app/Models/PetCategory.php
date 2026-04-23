<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
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
     * Relationship: PetCategory có nhiều PetBreeds
     */
    public function breeds()
    {
        return $this->hasMany(PetBreed::class)->active();
    }

    /**
     * Relationship: PetCategory có nhiều Pets
     */
    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    /**
     * Scope: chỉ lấy categories active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('deleted_at');
    }

    /**
     * Get total pets in this category
     */
    public function getTotalPetsAttribute()
    {
        return $this->pets()->count();
    }
}
