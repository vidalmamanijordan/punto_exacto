<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Path\StorePathRequest;
use App\Http\Requests\Path\UpdatePathRequest;
use App\Http\Resources\PathResource;
use App\Services\Path\PathService;
use Illuminate\Http\JsonResponse;
use Laravel\Mcp\Request;

class PathController extends Controller
{
    public function __construct(protected PathService $service) {}

    public function index(Request $request): JsonResponse
    {
        $paths = $this->service->list(
            campusId: $request->integer('campus_id') ?: null
        );

        return response()->json(
            PathResource::collection($paths)
        );
    }

    public function store(StorePathRequest $request): JsonResponse
    {
        $path = $this->service->create(
            $request->validated()
        );

        return response()->json(
            new PathResource($path->load(['fromWaypoint', 'toWaypoint'])),
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(
            new PathResource(
                $this->service->find($id)
            )
        );
    }

    public function update(UpdatePathRequest $request, int $id): JsonResponse
    {
        $path = $this->service->update(
            $id,
            $request->validated()
        );

        return response()->json(
            new PathResource($path)
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(status: 204);
    }
}
