<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'event',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: Audit log thuộc về user thực hiện
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: lọc theo entity type
     */
    public function scopeForEntity($query, string $entityType)
    {
        return $query->where('entity_type', $entityType);
    }

    /**
     * Scope: lọc theo user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: lọc theo action (created, updated, deleted)
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Helper: log create action
     */
    public static function logCreate($model, $userId = null, $newValues = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => 'created',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'new_values' => $newValues ?? $model->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'event' => 'created',
        ]);
    }

    /**
     * Helper: log update action
     */
    public static function logUpdate($model, $userId = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => 'updated',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'old_values' => $oldValues ?? $model->getOriginal(),
            'new_values' => $newValues ?? $model->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'event' => 'updated',
        ]);
    }

    /**
     * Helper: log delete action
     */
    public static function logDelete($model, $userId = null, $oldValues = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => 'deleted',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'old_values' => $oldValues ?? $model->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'event' => 'deleted',
        ]);
    }
}
