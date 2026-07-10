<?php

namespace App\Services;

use App\Models\Campus;

class CampusService
{
    public function getAll()
    {
        return Campus::latest()->get();
    }

    public function create(array $data)
    {
        return Campus::create($data);
    }

    public function update(
        Campus $campus,
        array $data
    ) {
        $campus->update($data);
        return $campus->fresh();
    }

    public function delete(Campus $campus)
    {
        $campus->delete();
    }
}
