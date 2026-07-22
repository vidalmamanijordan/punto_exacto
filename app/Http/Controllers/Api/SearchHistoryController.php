<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSearchHistoryRequest;
use App\Http\Requests\UpdateSearchHistoryRequest;
use App\Http\Resources\SearchHistoryResource;
use App\Models\SearchHistory;
use App\Services\SearchHistoryService;

class SearchHistoryController extends Controller
{
    public function __construct(
        protected SearchHistoryService $service
    ) {}

    public function index()
    {
        return SearchHistoryResource::collection(
            $this->service->getAll()
        );
    }

    public function store(StoreSearchHistoryRequest $request)
    {
        $searchHistory = $this->service->create($request->validated());
        return new SearchHistoryResource($searchHistory->load([
            'user',
            'place',
        ]));
    }

    public function show(SearchHistory $searchHistory)
    {
        return new SearchHistoryResource($searchHistory->load([
            'user',
            'place',
        ]));
    }

    public function update(UpdateSearchHistoryRequest $request, SearchHistory $searchHistory)
    {
        $searchHistory = $this->service->update($searchHistory, $request->validated());
        return new SearchHistoryResource($searchHistory);
    }

    public function destroy(SearchHistory $searchHistory)
    {
        $this->service->delete($searchHistory);
        return response()->json([
            'message' => 'Historial de búsqueda eliminado correctamente.'
        ]);
    }
}
