<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Waypoint extends Model
{
    protected $fillable = [
        'campus_id',
        'name',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function places(): HasMany
    {
        return $this->hasMany(Place::class);
    }

    public function outgoingPaths(): HasMany
    {
        return $this->hasMany(Path::class, 'from_waypoint_id');
    }

    public function incomingPaths(): HasMany
    {
        return $this->hasMany(Path::class, 'to_waypoint_id');
    }
}
