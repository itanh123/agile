<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatusLog extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'status', 'changed_by', 'note', 'changed_at'];
    protected $casts = ['changed_at' => 'datetime'];
}
