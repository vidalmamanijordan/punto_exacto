<?php

namespace App\Services;

use App\Models\Favorite;

class FavoriteService
{
    public function getAll()
    {
        return Favorite::with([
            'user',
            'place',
        ])->latest()->get();
    }

    public function create(array $data)
    {
        return Favorite::create($data)->load([
            'user',
            'place',
        ]);
    }

    public function update(
        Favorite $favorite,
        array $data
    ) {
        $favorite->update($data);
        return $favorite->fresh()->load([
            'user',
            'place',
        ]);
    }

    public function delete(Favorite $favorite)
    {
        $favorite->delete();
    }
}
