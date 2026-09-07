<?php

namespace App\Services\Path;

use App\Models\Path;
use App\Models\Waypoint;
use App\Services\Routing\GeoService;
use Illuminate\Support\Collection;

class PathService
{
    public function __construct(protected GeoService $geoService) {}

    /**
     * Lista los caminos, opcionalmente filtrados por campus
     * (a través de los waypoints que pertenecen a ese campus).
     */
    public function list(?int $campusId = null): Collection
    {
        return Path::query()
            ->with(['fromWaypoint', 'toWaypoint'])
            ->when(
                $campusId !== null,
                fn($query) => $query->whereHas(
                    'fromWaypoint',
                    fn($q) => $q->where('campus_id', $campusId)
                )
            )
            ->get();
    }

    public function find(int $id): Path
    {
        return Path::with(['fromWaypoint', 'toWaypoint'])
            ->findOrFail($id);
    }

    /**
     * Crea un nuevo camino. Si no se envía la distancia,
     * se calcula automáticamente con la fórmula Haversine
     * usando las coordenadas de los waypoints involucrados.
     */
    public function create(array $data): Path
    {
        if (empty($data['distance'])) {
            $data['distance'] = $this->calculateDistance(
                $data['from_waypoint_id'],
                $data['to_waypoint_id']
            );
        }

        return Path::create($data);
    }

    public function update(int $id, array $data): Path
    {
        $path = Path::findOrFail($id);

        $path->update($data);

        return $path->load(['fromWaypoint', 'toWaypoint']);
    }

    public function delete(int $id): void
    {
        Path::findOrFail($id)->delete();
    }

    /**
     * Calcula la distancia en metros entre dos waypoints
     * usando sus coordenadas geográficas.
     */
    protected function calculateDistance(int $fromWaypointId, int $toWaypointId): float
    {
        $from = Waypoint::findOrFail($fromWaypointId);
        $to = Waypoint::findOrFail($toWaypointId);

        return round(
            $this->geoService->distanceInMeters(
                (float) $from->latitude,
                (float) $from->longitude,
                (float) $to->latitude,
                (float) $to->longitude
            ),
            2
        );
    }
}
