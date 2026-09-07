<?php

namespace App\Services\Directions;

use App\Models\Waypoint;
use App\Services\Routing\GeoService;
use Illuminate\Support\Collection;

class InstructionService
{
    protected const STRAIGHT_THRESHOLD = 15;
    protected const SLIGHT_TURN_THRESHOLD = 45;
    protected const U_TURN_THRESHOLD = 135;

    public function __construct(
        protected GeoService $geoService
    ) {}

    /**
     * Genera instrucciones de texto a partir de una secuencia
     * ordenada de waypoints y la distancia real de cada tramo
     * (calculada por Dijkstra, no recalculada aquí).
     *
     * @param  Collection<Waypoint>  $waypoints
     * @param  array<float>  $segmentDistances  Una distancia por cada
     *         par de waypoints consecutivos (count = waypoints - 1).
     */
    public function generate(
        Collection $waypoints,
        array $segmentDistances,
        string $destinationName
    ): array {
        $waypoints = $waypoints->values();

        if ($waypoints->count() < 2) {
            return [];
        }

        $steps = [];
        $previousBearing = null;

        $lastIndex = $waypoints->count() - 2;

        for ($i = 0; $i <= $lastIndex; $i++) {

            /** @var Waypoint $current */
            $current = $waypoints[$i];

            /** @var Waypoint $next */
            $next = $waypoints[$i + 1];

            $distance = round($segmentDistances[$i] ?? 0);

            $bearing = $this->geoService->bearing(
                (float) $current->latitude,
                (float) $current->longitude,
                (float) $next->latitude,
                (float) $next->longitude
            );

            $isLastSegment = $i === $lastIndex;

            $instruction = $this->buildInstruction(
                $distance,
                $bearing,
                $previousBearing,
                $isLastSegment ? $destinationName : null
            );

            $steps[] = [
                'instruction' => $instruction,
                'distance' => $distance,
            ];

            $previousBearing = $bearing;
        }

        $steps[] = [
            'instruction' => "Has llegado a {$destinationName}.",
            'distance' => 0,
        ];

        return $steps;
    }

    protected function buildInstruction(
        float $distance,
        float $bearing,
        ?float $previousBearing,
        ?string $destinationName
    ): string {
        if ($previousBearing === null) {
            $instruction = "Camina {$distance}m hacia el "
                . $this->cardinalDirection($bearing);
        } else {
            $turn = $this->classifyTurn(
                $this->angleDifference($previousBearing, $bearing)
            );

            $instruction = $turn === 'recto'
                ? "Continúa recto {$distance}m"
                : "Gira {$turn} y camina {$distance}m";
        }

        if ($destinationName !== null) {
            $instruction .= " hasta llegar a {$destinationName}";
        }

        return $instruction;
    }

    protected function angleDifference(
        float $bearingFrom,
        float $bearingTo
    ): float {
        $diff = $bearingTo - $bearingFrom;

        return fmod($diff + 540, 360) - 180;
    }

    protected function classifyTurn(float $angle): string
    {
        $absoluteAngle = abs($angle);

        if ($absoluteAngle < self::STRAIGHT_THRESHOLD) {
            return 'recto';
        }

        if ($absoluteAngle < self::SLIGHT_TURN_THRESHOLD) {
            return $angle > 0
                ? 'levemente a la derecha'
                : 'levemente a la izquierda';
        }

        if ($absoluteAngle < self::U_TURN_THRESHOLD) {
            return $angle > 0
                ? 'a la derecha'
                : 'a la izquierda';
        }

        return 'en U (regresa)';
    }

    protected function cardinalDirection(float $bearing): string
    {
        $directions = [
            'norte',
            'noreste',
            'este',
            'sureste',
            'sur',
            'suroeste',
            'oeste',
            'noroeste',
        ];

        $index = (int) round($bearing / 45) % 8;

        return $directions[$index];
    }
}
