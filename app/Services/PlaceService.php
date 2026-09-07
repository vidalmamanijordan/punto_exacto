<?php

namespace App\Services;

use App\Models\Place;

class PlaceService
{
    public function getAll(?int $campusId = null)
    {
        return Place::with([
            'campus',
            'category',
        ])
            ->when(
                $campusId !== null,
                fn($query) => $query->where('campus_id', $campusId)
            )
            ->latest()
            ->get();
    }

    public function create(array $data)
    {
        return Place::create($data);
    }

    public function update(
        Place $place,
        array $data
    ) {
        $place->update($data);
        return $place->fresh();
    }

    public function delete(
        Place $place
    ) {
        $place->delete();
    }
}
