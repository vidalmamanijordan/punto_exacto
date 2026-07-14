<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(
        protected FavoriteService $service
    ) {}

    public function index()
    {
        return FavoriteResource::collection(
            $this->service->getAll()
        );
    }

    public function store(Request $request)
    {
        $favorite = $this->service->create(
            $request->validated()
        );

        return new FavoriteResource($favorite);
    }

    public function show(Favorite $favorite)
    {
        return new FavoriteResource(
            $favorite->load([
                'user',
                'place',
            ])
        );
    }

    public function update(UpdateFavoriteRequest $request, Favorite $favorite)
    {
        $favorite = $this->service->update(
            $favorite,
            $request->validated()
        );

        return new FavoriteResource($favorite);
    }

    public function destroy(Favorite $favorite)
    {
        $this->service->delete($favorite);

        return response()->json([
            'message' => 'Favorito eliminado correctamente.',
        ]);
    }
}
