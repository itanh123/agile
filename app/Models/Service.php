<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'service_type',
        'description',
        'price',
        'duration_minutes',
        'service_category_id',
        'is_active',
        'is_featured',
        'sort_order',
        'requires_staff',
        'deleted_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'requires_staff' => 'boolean',
        'sort_order' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Service thuộc về ServiceCategory
     */
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Relationship: Service thuộc về nhiều Bookings (pivot)
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot(['quantity', 'unit_price', 'line_total'])
            ->withTimestamps();
    }

    /**
     * Scope: chỉ lấy services active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('deleted_at');
    }

    /**
     * Scope: chỉ lấy featured services
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->active();
    }

    /**
     * Scope: lọc theo service type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('service_type', $type);
    }

    /**
     * Scope: lọc theo category
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('service_category_id', $categoryId);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    /**
     * Get duration formatted (e.g., "1h 30m")
     */
    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } elseif ($hours > 0) {
            return $hours . 'h';
        }

        return $minutes . 'm';
    }

    /**
     * Get service type label
     */
    public function getServiceTypeLabelAttribute()
    {
        return match($this->service_type) {
            'grooming' => 'Làm đẹp',
            'vaccination' => 'Tiêm phòng',
            'spa' => 'Spa',
            'checkup' => 'Khám tổng quát',
            'surgery' => 'Phẫu thuật',
            'other' => 'Khác',
            default => $this->service_type,
        };
    }
}
