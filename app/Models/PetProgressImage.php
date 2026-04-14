<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetProgressImage extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'image_path', 'stage', 'caption', 'taken_at'];
    protected $casts = ['taken_at' => 'datetime'];
}
