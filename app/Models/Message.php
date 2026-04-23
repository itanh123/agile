<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sender',
        'content',
        'sent_at',
        'is_read',
        'deleted_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Message thuộc về user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false)->whereNull('deleted_at');
    }

    /**
     * Scope: messages from specific sender
     */
    public function scopeFromSender($query, $sender)
    {
        return $query->where('sender', $sender);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Get sender label
     */
    public function getSenderLabelAttribute()
    {
        return match($this->sender) {
            'user' => 'Khách hàng',
            'staff' => 'Nhân viên',
            'ai' => 'AI Assistant',
            default => $this->sender,
        };
    }
}
