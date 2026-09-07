<?php

namespace App\Services\Waypoint;

use App\Models\Waypoint;
use Illuminate\Support\Collection;

class WaypointService
{
    /**
     * Lista los waypoints de un campus (o todos si no se especifica).
     */
    public function list(?int $campusId = null): Collection
    {
        return Waypoint::query()
            ->when(
                $campusId !== null,
                fn($query) => $query->where('campus_id', $campusId)
            )
            ->orderBy('name')
            ->get();
    }

    public function find(int $id): Waypoint
    {
        return Waypoint::findOrFail($id);
    }

    public function create(array $data): Waypoint
    {
        return Waypoint::create($data);
    }

    public function update(int $id, array $data): Waypoint
    {
        $waypoint = $this->find($id);

        $waypoint->update($data);

        return $waypoint;
    }

    /**
     * Elimina un waypoint.
     *
     * Al tener FK con onDelete cascade en 'paths' y onDelete
     * set null en 'places', al borrar un waypoint se eliminan
     * automáticamente sus paths asociados, y los places que
     * apuntaban a él quedan con waypoint_id en null.
     */
    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
