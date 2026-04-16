<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetProgressImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'image_path',
        'uploaded_by',
        'caption',
        'taken_at'
    ];

    protected $casts = ['taken_at' => 'datetime'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
