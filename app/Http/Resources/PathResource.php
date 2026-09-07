<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PathResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_waypoint_id' => $this->from_waypoint_id,
            'to_waypoint_id' => $this->to_waypoint_id,
            'distance' => $this->distance,
            'is_bidirectional' => $this->is_bidirectional,
            'is_active' => $this->is_active,
            'from_waypoint' => new WaypointResource($this->whenLoaded('fromWaypoint')),
            'to_waypoint' => new WaypointResource($this->whenLoaded('toWaypoint')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
