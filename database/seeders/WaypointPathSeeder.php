<?php

namespace Database\Seeders;

use App\Models\Path;
use App\Models\Place;
use App\Models\Waypoint;
use Illuminate\Database\Seeder;

class WaypointPathSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Waypoints del campus Lima
        |--------------------------------------------------------------------------
        |
        | Ajusta el campus_id si en tu BD el campus Lima tiene otro id.
        |
        */

        $campusId = 1;

        $entrada = Waypoint::create([
            'campus_id' => $campusId,
            'name' => 'Entrada Principal',
            'latitude' => -12.0430000,
            'longitude' => -77.0280000,
            'is_active' => true,
        ]);

        $cruceCentral = Waypoint::create([
            'campus_id' => $campusId,
            'name' => 'Cruce Central',
            'latitude' => -12.0432000,
            'longitude' => -77.0282000,
            'is_active' => true,
        ]);

        $accesoBiblioteca = Waypoint::create([
            'campus_id' => $campusId,
            'name' => 'Acceso Biblioteca Central',
            'latitude' => -12.0431800,
            'longitude' => -77.0282400,
            'is_active' => true,
        ]);

        $accesoLaboratorio = Waypoint::create([
            'campus_id' => $campusId,
            'name' => 'Acceso Laboratorio de Redes',
            'latitude' => -12.0435000,
            'longitude' => -77.0284000,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Caminos (aristas del grafo)
        |--------------------------------------------------------------------------
        */

        Path::create([
            'from_waypoint_id' => $entrada->id,
            'to_waypoint_id' => $cruceCentral->id,
            'distance' => 30,
            'is_bidirectional' => true,
            'is_active' => true,
        ]);

        Path::create([
            'from_waypoint_id' => $cruceCentral->id,
            'to_waypoint_id' => $accesoBiblioteca->id,
            'distance' => 20,
            'is_bidirectional' => true,
            'is_active' => true,
        ]);

        Path::create([
            'from_waypoint_id' => $cruceCentral->id,
            'to_waypoint_id' => $accesoLaboratorio->id,
            'distance' => 50,
            'is_bidirectional' => true,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Vincular los Places existentes a su waypoint de acceso
        |--------------------------------------------------------------------------
        */

        Place::where('name', 'Biblioteca Central')
            ->update(['waypoint_id' => $accesoBiblioteca->id]);

        Place::where('name', 'Laboratorio de Redes')
            ->update(['waypoint_id' => $accesoLaboratorio->id]);
    }
}
