<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sender', 'content', 'sent_at', 'is_read'];
    protected $casts = ['sent_at' => 'datetime'];
}
