<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampusRequest;
use App\Http\Requests\UpdateCampusRequest;
use App\Http\Resources\CampusResource;
use App\Models\Campus;
use App\Services\CampusService;

class CampusController extends Controller
{
    public function __construct(
        protected CampusService $service
    ) {}

    public function index()
    {
        return CampusResource::collection(
            $this->service->getAll()
        );
    }

    public function store(StoreCampusRequest $request)
    {
        $campus = $this->service->create(
            $request->validated()
        );

        return new CampusResource($campus);
    }

    public function show(Campus $campus)
    {
        return new CampusResource($campus);
    }


    public function update(UpdateCampusRequest $request, Campus $campus)
    {
        $campus = $this->service->update(
            $campus,
            $request->validated()
        );

        return new CampusResource($campus);
    }

    public function destroy(Campus $campus)
    {
        $this->service->delete($campus);

        return response()->json([
            'message' => 'Campus eliminado satisfactoriamente.'
        ]);
    }
}
