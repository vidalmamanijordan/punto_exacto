<?php

namespace App\Services\Routing;

use App\Models\Path;
use App\Models\Waypoint;

class PathfindingService
{
    /**
     * Calcula el camino más corto entre dos waypoints
     * dentro de un campus, usando el algoritmo de Dijkstra.
     *
     * Devuelve null si no existe un camino posible entre ambos.
     */
    public function findShortestPath(
        int $fromWaypointId,
        int $toWaypointId,
        int $campusId
    ): ?array {
        $graph = $this->buildGraph($campusId);

        if (
            !isset($graph[$fromWaypointId]) ||
            !isset($graph[$toWaypointId])
        ) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Inicializar distancias
        |--------------------------------------------------------------------------
        */

        $distances = [];
        $previous = [];
        $unvisited = [];

        foreach (array_keys($graph) as $waypointId) {
            $distances[$waypointId] = INF;
            $previous[$waypointId] = null;
            $unvisited[$waypointId] = true;
        }

        $distances[$fromWaypointId] = 0;

        /*
        |--------------------------------------------------------------------------
        | Recorrer el grafo
        |--------------------------------------------------------------------------
        */

        while (!empty($unvisited)) {

            $current = $this->closestUnvisited(
                $distances,
                $unvisited
            );

            if ($current === null || $current === $toWaypointId) {
                break;
            }

            unset($unvisited[$current]);

            foreach ($graph[$current] as $neighborId => $weight) {

                $alternative = $distances[$current] + $weight;

                if ($alternative < $distances[$neighborId]) {
                    $distances[$neighborId] = $alternative;
                    $previous[$neighborId] = $current;
                }
            }
        }

        if ($distances[$toWaypointId] === INF) {
            return null; // no hay camino posible
        }

        $waypointIds = $this->reconstructPath(
            $previous,
            $toWaypointId
        );

        return [
            'distance' => $distances[$toWaypointId],
            'waypoint_ids' => $waypointIds,
            'segment_distances' => $this->extractSegmentDistances(
                $graph,
                $waypointIds
            ),
        ];
    }

    /**
     * Extrae la distancia real (la misma que usó Dijkstra) de cada
     * tramo individual del camino, en el mismo orden que $waypointIds.
     *
     * Esto evita que otros servicios (como InstructionService) tengan
     * que recalcular la distancia con Haversine, que podría no coincidir
     * con la distancia real registrada en el Path (por ejemplo, si el
     * camino real no es una línea recta).
     */
    protected function extractSegmentDistances(
        array $graph,
        array $waypointIds
    ): array {
        $segments = [];

        for ($i = 0; $i < count($waypointIds) - 1; $i++) {
            $from = $waypointIds[$i];
            $to = $waypointIds[$i + 1];

            $segments[] = $graph[$from][$to] ?? 0;
        }

        return $segments;
    }

    /**
     * Construye el grafo de adyacencia: para cada waypoint,
     * una lista de [waypoint vecino => distancia].
     */
    protected function buildGraph(int $campusId): array
    {
        $waypointIds = Waypoint::where('campus_id', $campusId)
            ->where('is_active', true)
            ->pluck('id');

        $graph = [];

        foreach ($waypointIds as $id) {
            $graph[$id] = [];
        }

        $paths = Path::whereIn('from_waypoint_id', $waypointIds)
            ->where('is_active', true)
            ->get();

        foreach ($paths as $path) {

            if (
                !isset($graph[$path->from_waypoint_id]) ||
                !isset($graph[$path->to_waypoint_id])
            ) {
                continue;
            }

            $graph[$path->from_waypoint_id][$path->to_waypoint_id] = (float) $path->distance;

            if ($path->is_bidirectional) {
                $graph[$path->to_waypoint_id][$path->from_waypoint_id] = (float) $path->distance;
            }
        }

        return $graph;
    }

    /**
     * Encuentra el waypoint no visitado con menor distancia acumulada.
     */
    protected function closestUnvisited(
        array $distances,
        array $unvisited
    ): ?int {
        $closest = null;
        $shortest = INF;

        foreach (array_keys($unvisited) as $waypointId) {
            if ($distances[$waypointId] < $shortest) {
                $shortest = $distances[$waypointId];
                $closest = $waypointId;
            }
        }

        return $closest;
    }

    /**
     * Reconstruye la secuencia de waypoints desde el origen
     * hasta el destino, siguiendo el rastro de "previous".
     */
    protected function reconstructPath(
        array $previous,
        int $toWaypointId
    ): array {
        $path = [];
        $current = $toWaypointId;

        while ($current !== null) {
            array_unshift($path, $current);
            $current = $previous[$current];
        }

        return $path;
    }
}
