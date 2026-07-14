<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Place extends Model
{
    protected $fillable = [
        'campus_id',
        'category_id',
        'name',
        'description',
        'building',
        'floor',
        'room',
        'image',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
