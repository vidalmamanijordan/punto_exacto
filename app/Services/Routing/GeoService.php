<?php

namespace App\Services\Routing;

class GeoService
{
    /**
     * Calcula la distancia en metros entre dos coordenadas
     * geográficas usando la fórmula de Haversine.
     *
     * Es una aproximación estándar que asume la Tierra como
     * una esfera; suficientemente precisa para distancias
     * cortas dentro de un campus.
     */
    public function distanceInMeters(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $earthRadius = 6371000; // radio de la Tierra en metros

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * sin($lngDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Calcula el rumbo (bearing) en grados desde el punto 1 hacia
     * el punto 2, donde 0° = Norte, 90° = Este, 180° = Sur, 270° = Oeste.
     *
     * Se usa para saber "hacia dónde" avanza un tramo del camino,
     * y así poder comparar tramos consecutivos y detectar giros.
     */

    public function bearing(
        float $lat1,
        float $lng1,
        float $lat2,
        float $lng2
    ): float {
        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $lngDelta = deg2rad($lng2 - $lng1);

        $y = sin($lngDelta) * cos($lat2Rad);

        $x = cos($lat1Rad) * sin($lat2Rad)
            - sin($lat1Rad) * cos($lat2Rad) * cos($lngDelta);

        $bearing = rad2deg(atan2($y, $x));

        return fmod($bearing + 360, 360);
    }
}
