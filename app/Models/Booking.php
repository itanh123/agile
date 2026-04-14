<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'pet_id',
        'staff_id',
        'promotion_id',
        'appointment_at',
        'service_mode',
        'status',
        'payment_status',
        'payment_method',
        'subtotal',
        'discount_amount',
        'total_amount',
        'note',
    ];

    protected $casts = ['appointment_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function pet() { return $this->belongsTo(Pet::class); }
    public function staff() { return $this->belongsTo(User::class, 'staff_id'); }
    public function promotion() { return $this->belongsTo(Promotion::class); }
    public function services() { return $this->belongsToMany(Service::class, 'booking_services')->withPivot(['quantity', 'unit_price', 'line_total'])->withTimestamps(); }
    public function payment() { return $this->hasOne(Payment::class); }
    public function images() { return $this->hasMany(PetProgressImage::class); }
    public function logs() { return $this->hasMany(BookingStatusLog::class); }
    public function review() { return $this->hasOne(Review::class); }
}
