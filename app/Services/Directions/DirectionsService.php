<?php

namespace App\Services\Directions;

use App\Http\Resources\PlaceResource;
use App\Http\Resources\WaypointResource;
use App\Models\Place;
use App\Models\Waypoint;
use App\Services\Routing\PathfindingService;
use App\Services\Routing\WaypointFinderService;
use Illuminate\Support\Collection;

class DirectionsService
{
    /**
     * Velocidad promedio de caminata de una persona,
     * en metros por segundo (~5 km/h).
     */
    protected const WALKING_SPEED_MPS = 1.4;

    public function __construct(
        protected WaypointFinderService $waypointFinder,
        protected PathfindingService $pathfinding,
        protected InstructionService $instructionService,
    ) {}

    /**
     * Calcula la ruta caminable desde una coordenada de origen
     * hasta el punto de acceso de un lugar.
     */
    public function getRoute(
        float $originLat,
        float $originLng,
        int $placeId
    ): array {
        $place = Place::with(['waypoint', 'campus'])
            ->where('is_active', true)
            ->find($placeId);

        if (! $place) {
            return $this->errorResponse(
                'El lugar solicitado no existe o no está disponible.'
            );
        }

        if (! $place->waypoint) {
            return $this->errorResponse(
                'Este lugar todavía no tiene un punto de acceso mapeado para navegación.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Encontrar el waypoint más cercano al origen del usuario
        |--------------------------------------------------------------------------
        */

        $originWaypoint = $this->waypointFinder->findNearest(
            $originLat,
            $originLng,
            $place->campus_id
        );

        if (! $originWaypoint) {
            return $this->errorResponse(
                'No se encontraron puntos de referencia cercanos a tu ubicación en este campus.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Calcular el camino más corto entre ambos waypoints
        |--------------------------------------------------------------------------
        */

        $result = $this->pathfinding->findShortestPath(
            $originWaypoint->id,
            $place->waypoint_id,
            $place->campus_id
        );

        if (! $result) {
            return $this->fallbackStraightLineResponse(
                $place,
                $originLat,
                $originLng,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Construir la respuesta
        |--------------------------------------------------------------------------
        */

        return $this->successResponse(
            $place,
            $originWaypoint,
            $result
        );
    }

    /**
     * Arma la respuesta exitosa con el detalle de la ruta.
     */
    protected function successResponse(
        Place $place,
        Waypoint $originWaypoint,
        array $result
    ): array {
        $waypoints = $this->orderedWaypoints(
            $result['waypoint_ids']
        );

        $distance = $result['distance'];

        return [
            'success' => true,

            'message' => "La ruta hacia {$place->name} tiene "
                .round($distance).' metros.',

            'data' => [
                'place' => new PlaceResource($place),

                'distance_meters' => round($distance, 2),

                'duration_minutes' => round(
                    $distance / self::WALKING_SPEED_MPS / 60,
                    1
                ),
                'origin_waypoint' => new WaypointResource(
                    $originWaypoint
                ),
                'waypoints' => WaypointResource::collection(
                    $waypoints
                ),
                'steps' => $this->instructionService->generate(
                    $waypoints,
                    $result['segment_distances'],
                    $place->name
                ),
            ],
        ];
    }

    /**
     * Obtiene los waypoints del recorrido en el orden correcto
     * (Dijkstra devuelve solo los IDs en orden; aquí cargamos
     * los modelos completos respetando esa secuencia).
     */
    protected function orderedWaypoints(array $waypointIds): Collection
    {
        $waypoints = Waypoint::whereIn('id', $waypointIds)
            ->get()
            ->keyBy('id');

        return collect($waypointIds)
            ->map(fn ($id) => $waypoints->get($id))
            ->filter()
            ->values();
    }

    /**
     * Fallback: cuando no hay paths que conecten los waypoints,
     * devuelve origen → waypoint del lugar (línea recta).
     * Cuando se agreguen paths, Dijkstra tomará el control automáticamente.
     */
    protected function fallbackStraightLineResponse(
        Place $place,
        float $originLat,
        float $originLng,
    ): array {
        $destLat = (float) $place->waypoint->latitude;
        $destLng = (float) $place->waypoint->longitude;

        $distanceMeters = $this->haversineDistance(
            $originLat,
            $originLng,
            $destLat,
            $destLng,
        );

        $durationMinutes = $distanceMeters / 83;

        return [
            'success' => true,
            'message' => "La ruta hacia {$place->name} tiene ".round($distanceMeters).' metros.',
            'data' => [
                'place' => new PlaceResource($place),
                'distance_meters' => round($distanceMeters, 2),
                'duration_minutes' => round($durationMinutes, 2),
                'origin_waypoint' => [
                    'id' => 0,
                    'name' => 'Tu ubicación',
                    'latitude' => (string) $originLat,
                    'longitude' => (string) $originLng,
                ],
                'waypoints' => [
                    [
                        'id' => 0,
                        'name' => 'Tu ubicación',
                        'latitude' => (string) $originLat,
                        'longitude' => (string) $originLng,
                    ],
                    [
                        'id' => $place->waypoint->id,
                        'name' => $place->name,
                        'latitude' => (string) $destLat,
                        'longitude' => (string) $destLng,
                    ],
                ],
                'steps' => [],
            ],
        ];
    }

    /**
     * Calcula la distancia en metros entre dos coordenadas (fórmula Haversine).
     */
    protected function haversineDistance(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2,
    ): float {
        $R = 6371000;
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $dphi = deg2rad($lat2 - $lat1);
        $dlambda = deg2rad($lng2 - $lng1);

        $a = sin($dphi / 2) ** 2 +
             cos($phi1) * cos($phi2) * sin($dlambda / 2) ** 2;

        return 2 * $R * asin(sqrt($a));
    }

    /**
     * Respuesta de error, con la misma forma que el éxito
     * para mantener el contrato consistente.
     */
    protected function errorResponse(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => null,
        ];
    }
}
