<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Path extends Model
{
    protected $fillable = [
        'from_waypoint_id',
        'to_waypoint_id',
        'distance',
        'is_bidirectional',
        'is_active',
    ];

    protected $casts = [
        'is_bidirectional' => 'boolean',
        'is_active' => 'boolean',
        'distance' => 'float',
    ];

    public function fromWaypoint(): BelongsTo
    {
        return $this->belongsTo(Waypoint::class, 'from_waypoint_id');
    }

    public function toWaypoint(): BelongsTo
    {
        return $this->belongsTo(Waypoint::class, 'to_waypoint_id');
    }
}
