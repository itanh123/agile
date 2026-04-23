<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionUse extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'booking_id',
        'user_id',
        'discount_amount',
        'used_at',
        'note',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'discount_amount' => 'decimal:2',
    ];

    /**
     * Relationship: PromotionUse thuộc về promotion
     */
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    /**
     * Relationship: PromotionUse thuộc về booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: PromotionUse thuộc về user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: lọc theo user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: lọc theo promotion
     */
    public function scopeByPromotion($query, $promotionId)
    {
        return $query->where('promotion_id', $promotionId);
    }

    /**
     * Scope: lọc theo ngày
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('used_at', $date);
    }

    /**
     * Get total discount amount by promotion
     */
    public static function getTotalDiscountByPromotion($promotionId)
    {
        return self::where('promotion_id', $promotionId)->sum('discount_amount');
    }
}
