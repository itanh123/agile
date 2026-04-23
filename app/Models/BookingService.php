<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'quantity',
        'unit_price',
        'line_total',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    /**
     * Relationship: BookingService thuộc về booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: BookingService thuộc về service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, ',', '.') . ' ₫';
    }

    /**
     * Get formatted line total
     */
    public function getFormattedLineTotalAttribute()
    {
        return number_format($this->line_total, 0, ',', '.') . ' ₫';
    }
}
