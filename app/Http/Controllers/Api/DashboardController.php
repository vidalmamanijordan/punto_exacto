<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'statistics' => $this->service->getStatistics(),
            'recent_search_histories' => $this->service->getRecentSearchHistories(),
            'recent_ratings' => $this->service->getRecentRatings(),
            'recent_favorites' => $this->service->getRecentFavorites(),
        ]);
    }
}
