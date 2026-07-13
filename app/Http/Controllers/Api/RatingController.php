<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Rating;
use App\Services\RatingService;

class RatingController extends Controller
{
    public function __construct(
        protected RatingService $service
    ) {}

    public function index()
    {
        return RatingResource::collection($this->service->getAll());
    }

    public function store(StoreRatingRequest $request)
    {
        $rating = $this->service->create($request->validated());
        return new RatingResource($rating);
    }

    public function show(Rating $rating)
    {
        return new RatingResource($rating->load([
            'user',
            'place',
        ]));
    }

    public function update(UpdateRatingRequest  $request, Rating $rating)
    {
        $rating = $this->service->update($rating, $request->validated());
        return new RatingResource($rating);
    }

    public function destroy(Rating $rating)
    {
        $this->service->delete($rating);
        return response()->json([
            'message' => 'Valoración eliminada correctamente.'
        ]);
    }
}
