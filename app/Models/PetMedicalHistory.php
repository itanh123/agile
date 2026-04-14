<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetMedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'pet_medical_history';

    protected $fillable = ['pet_id', 'diagnosis', 'treatment', 'veterinarian', 'visit_date', 'notes'];
}
