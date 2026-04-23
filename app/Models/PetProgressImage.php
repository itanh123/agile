<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PetProgressImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'image_path',
        'uploaded_by',
        'caption',
        'taken_at',
        'deleted_at',
    ];

    protected $casts = [
        'taken_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: PetProgressImage thuộc về booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: PetProgressImage được upload bởi user
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope: lọc theo booking
     */
    public function scopeForBooking($query, $bookingId)
    {
        return $query->where('booking_id', $bookingId);
    }

    /**
     * Scope: lọc theo uploaded_by
     */
    public function scopeByUploader($query, $userId)
    {
        return $query->where('uploaded_by', $userId);
    }

    /**
     * Get image URL attribute (helper)
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}
