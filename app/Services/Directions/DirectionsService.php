<?php

namespace App\Services\Directions;

use App\Models\Place;
use App\Models\Waypoint;
use App\Services\Routing\PathfindingService;
use App\Services\Routing\WaypointFinderService;

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

        if (!$place) {
            return $this->errorResponse(
                'El lugar solicitado no existe o no está disponible.'
            );
        }

        if (!$place->waypoint) {
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

        if (!$originWaypoint) {
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

        if (!$result) {
            return $this->errorResponse(
                'No se encontró una ruta caminable hasta ese lugar.'
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
                . round($distance) . ' metros.',

            'data' => [
                'place' => new \App\Http\Resources\PlaceResource($place),

                'distance_meters' => round($distance, 2),

                'duration_minutes' => round(
                    $distance / self::WALKING_SPEED_MPS / 60,
                    1
                ),
                'origin_waypoint' => new \App\Http\Resources\WaypointResource(
                    $originWaypoint
                ),
                'waypoints' => \App\Http\Resources\WaypointResource::collection(
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
    protected function orderedWaypoints(array $waypointIds): \Illuminate\Support\Collection
    {
        $waypoints = Waypoint::whereIn('id', $waypointIds)
            ->get()
            ->keyBy('id');

        return collect($waypointIds)
            ->map(fn($id) => $waypoints->get($id))
            ->filter()
            ->values();
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
