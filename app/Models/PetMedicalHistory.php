<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetMedicalHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pet_medical_history';

    protected $fillable = [
        'pet_id',
        'diagnosis',
        'treatment',
        'veterinarian',
        'visit_date',
        'notes',
        'deleted_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: PetMedicalHistory thuộc về pet
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    /**
     * Scope: lọc theo pet
     */
    public function scopeForPet($query, $petId)
    {
        return $query->where('pet_id', $petId);
    }

    /**
     * Scope: lọc theo ngày khám
     */
    public function scopeFromDate($query, $date)
    {
        return $query->where('visit_date', '>=', $date);
    }

    /**
     * Scope: active records only
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }
}
