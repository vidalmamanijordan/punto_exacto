<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
