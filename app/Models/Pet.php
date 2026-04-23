<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'breed_id',
        'name',
        'avatar',
        'gender',
        'date_of_birth',
        'weight',
        'color',
        'allergies',
        'health_status',
        'notes',
        'deleted_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Pet thuộc về user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Pet thuộc về category
     */
    public function category()
    {
        return $this->belongsTo(PetCategory::class);
    }

    /**
     * Relationship: Pet thuộc về breed
     */
    public function breed()
    {
        return $this->belongsTo(PetBreed::class);
    }

    /**
     * Relationship: Pet có nhiều bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relationship: Pet có nhiều medical histories
     */
    public function medicalHistories()
    {
        return $this->hasMany(PetMedicalHistory::class);
    }

    /**
     * Scope: pets của user cụ thể
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: pets theo category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: pets theo breed
     */
    public function scopeByBreed($query, $breedId)
    {
        return $query->where('breed_id', $breedId);
    }

    /**
     * Get age in years/months
     */
    public function getAgeAttribute()
    {
        return $this->age();
    }

    /**
     * Get age in years/months as a method
     */
    public function age()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        $years = Carbon::parse($this->date_of_birth)->diffInYears(now());
        $months = Carbon::parse($this->date_of_birth)->diffInMonths(now()) % 12;

        if ($years > 0) {
            return $years . ' tuổi ' . ($months > 0 ? $months . ' tháng' : '');
        }

        return $months . ' tháng tuổi';
    }

    /**
     * Get age in months (for calculation)
     */
    public function getAgeInMonthsAttribute()
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return \Carbon\Carbon::parse($this->date_of_birth)->diffInMonths(now());
    }
}
