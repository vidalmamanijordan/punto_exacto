<?php

namespace App\Services\Routing;

use App\Models\Waypoint;
use Illuminate\Database\Eloquent\Collection;

class WaypointFinderService
{
    public function __construct(protected GeoService $geoService) {}

    /**
     * Encuentra el waypoint más cercano a una coordenada dada,
     * dentro de un campus específico.
     */
    public function findNearest(
        float $latitude,
        float $longitude,
        int $campusId
    ): ?Waypoint {
        $waypoints = Waypoint::where('campus_id', $campusId)
            ->where('is_active', true)
            ->get();

        if ($waypoints->isEmpty()) {
            return null;
        }

        return $this->sortByDistance(
            $waypoints,
            $latitude,
            $longitude
        )->first();
    }

    /**
     * Ordena una colección de waypoints por cercanía
     * a una coordenada dada.
     */
    protected function sortByDistance(
        Collection $waypoints,
        float $latitude,
        float $longitude
    ): Collection {
        return $waypoints
            ->map(function (Waypoint $waypoint) use ($latitude, $longitude) {
                $waypoint->distance_to_point = $this->geoService->distanceInMeters(
                    $latitude,
                    $longitude,
                    (float) $waypoint->latitude,
                    (float) $waypoint->longitude
                );

                return $waypoint;
            })
            ->sortBy('distance_to_point')
            ->values();
    }
}
