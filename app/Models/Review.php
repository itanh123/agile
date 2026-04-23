<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'rating',
        'title',
        'comment',
        'is_public',
        'deleted_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_public' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Review thuộc về booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: Review thuộc về user (qua booking)
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id', // Foreign key on bookings table
            'id', // Foreign key on users table
            'booking_id', // Local key on reviews table
            'user_id' // Local key on bookings table
        );
    }

    /**
     * Scope: chỉ lấy reviews public
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true)->whereNull('deleted_at');
    }

    /**
     * Scope: lọc theo rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Get rating stars as string
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
