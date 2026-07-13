<?php

namespace App\Services;

use App\Models\Rating;

class RatingService
{
    public function getAll()
    {
        return Rating::with([
            'user',
            'place'
        ])->latest()->get();
    }

    public function create(array $data)
    {
        return Rating::create($data)->load([
            'user',
            'place',
        ]);
    }

    public function update(Rating $rating, array $data)
    {
        $rating->update($data);
        return $rating->fresh()->load([
            'user',
            'place',
        ]);
    }

    public function delete(Rating $rating)
    {
        $rating->delete();
    }
}
