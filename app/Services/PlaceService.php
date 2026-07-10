<?php

namespace App\Services;

use App\Models\Place;

class PlaceService
{
    public function getAll()
    {
        return Place::with([
            'campus',
            'category',
        ])
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
