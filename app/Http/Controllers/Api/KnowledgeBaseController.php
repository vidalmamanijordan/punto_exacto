<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKnowledgeBaseRequest;
use App\Http\Requests\UpdateKnowledgeBaseRequest;
use App\Http\Resources\KnowledgeBaseResource;
use App\Models\KnowledgeBase;
use App\services\KnowledgeBaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KnowledgeBaseController extends Controller
{
    public function __construct(protected KnowledgeBaseService $service) {}

    public function index(): AnonymousResourceCollection
    {
        return KnowledgeBaseResource::collection(
            $this->service->getAll()
        );
    }

    public function store(
        StoreKnowledgeBaseRequest $request
    ): KnowledgeBaseResource {
        $knowledgeBase = $this->service->create(
            $request->validated()
        );
        return new KnowledgeBaseResource($knowledgeBase);
    }

    public function show(KnowledgeBase $knowledgeBase): KnowledgeBaseResource
    {
        return new KnowledgeBaseResource($knowledgeBase);
    }

    public function update(
        UpdateKnowledgeBaseRequest $request,
        KnowledgeBase $knowledgeBase
    ): KnowledgeBaseResource {
        $knowledgeBase = $this->service->update(
            $knowledgeBase,
            $request->validated()
        );
        return new KnowledgeBaseResource(
            $knowledgeBase
        );
    }

    public function destroy(KnowledgeBase $knowledgeBase): JsonResponse
    {
        $this->service->delete($knowledgeBase);

        return response()->json([
            'message' => 'Registro de la Base de Conocimiento eliminado correctamente.',
        ]);
    }

    public function activate(
        KnowledgeBase $knowledgeBase
    ): KnowledgeBaseResource {
        $knowledgeBase = $this->service->activate(
            $knowledgeBase
        );
        return new KnowledgeBaseResource(
            $knowledgeBase
        );
    }

    public function deactivate(
        KnowledgeBase $knowledgeBase
    ): KnowledgeBaseResource {
        $knowledgeBase = $this->service->deactivate(
            $knowledgeBase
        );
        return new KnowledgeBaseResource(
            $knowledgeBase
        );
    }

    public function active(): AnonymousResourceCollection
    {
        return KnowledgeBaseResource::collection(
            $this->service->getActive()
        );
    }
}
