<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Waypoint\StoreWaypointRequest;
use App\Http\Requests\Waypoint\UpdateWaypointRequest;
use App\Http\Resources\WaypointResource;
use App\Services\Waypoint\WaypointService;
use Illuminate\Http\JsonResponse;
use Laravel\Mcp\Request;

class WaypointController extends Controller
{
    public function __construct(protected WaypointService $service) {}

    public function index(Request $request): JsonResponse
    {
        $waypoints = $this->service->list(
            campusId: $request->integer('campus_id') ?: null
        );

        return response()->json(
            WaypointResource::collection($waypoints)
        );
    }

    public function store(StoreWaypointRequest $request): JsonResponse
    {
        $waypoint = $this->service->create(
            $request->validated()
        );

        return response()->json(
            new WaypointResource($waypoint),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            new WaypointResource(
                $this->service->find($id)
            )
        );
    }

    public function update(UpdateWaypointRequest $request, int $id): JsonResponse
    {
        $waypoint = $this->service->update(
            $id,
            $request->validated()
        );

        return response()->json(
            new WaypointResource($waypoint)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(status: 204);
    }
}
