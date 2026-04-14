<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'transaction_code', 'payment_method', 'status', 'amount', 'paid_at', 'note'];
    protected $casts = ['paid_at' => 'datetime'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
