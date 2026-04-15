<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'service_type', 'description', 'price', 'duration_minutes', 'is_active'];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot(['quantity', 'unit_price', 'line_total'])
            ->withTimestamps();
    }
}
