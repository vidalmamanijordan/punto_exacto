<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Directions\DirectionsRequest;
use App\Services\Directions\DirectionsService;
use Illuminate\Http\JsonResponse;

class DirectionsController extends Controller
{
    public function __construct(protected DirectionsService $service) {}

    public function index(DirectionsRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRoute(
                originLat: (float) $request->validated('origin_lat'),
                originLng: (float) $request->validated('origin_lng'),
                placeId: (int) $request->validated('place_id'),
            )
        );
    }
}
