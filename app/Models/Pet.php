<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'breed_id',
        'name',
        'gender',
        'date_of_birth',
        'weight',
        'color',
        'allergies',
        'notes',
        'avatar',
        'health_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'category_id');
    }

    public function breed()
    {
        return $this->belongsTo(PetBreed::class, 'breed_id');
    }

    public function age()
    {
        if (!$this->date_of_birth) {
            return null;
        }
        
        $birthDate = \Carbon\Carbon::parse($this->date_of_birth);
        $years = $birthDate->diffInYears(now());
        $months = $birthDate->diffInMonths(now()) % 12;
        
        if ($years > 0) {
            return $years . ' tuổi ' . ($months > 0 ? $months . ' tháng' : '');
        }
        
        return $months . ' tháng tuổi';
    }
}
