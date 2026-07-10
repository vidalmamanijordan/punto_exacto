<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use App\Services\PlaceService;

class PlaceController extends Controller
{
    public function __construct(
        protected PlaceService $service

    ) {}

    public function index()
    {
        return PlaceResource::collection(
            $this->service->getAll()
        );
    }

    public function store(StorePlaceRequest  $request)
    {
        $place = $this->service->create($request->validated());
        return new PlaceResource($place);
    }

    public function show(Place $place)
    {
        $place->load(
            'campus',
            'category',
        );
        return new PlaceResource($place);
    }

    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $place = $this->service->update($place, $request->validated());
        return new PlaceResource($place);
    }

    public function destroy(Place $place)
    {
        $this->service->delete($place);
        return response()->json([
            'message' => 'Lugar eliminado correctamente'
        ]);
    }
}
