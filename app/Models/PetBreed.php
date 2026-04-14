<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetBreed extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'is_active'];

    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'category_id');
    }
}
