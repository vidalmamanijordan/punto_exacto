<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $service
    ) {}

    /**
     * Obtiene toda la información del Dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        $period = $request->input('period', 30);

        $filters = [
            'period' => is_numeric($period) ? (int) $period : 30,
            'campus' => $request->input('campus'),
            'category' => $request->input('category'),
        ];

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            'summary' => [
                'statistics' => $this->service->getStatistics($filters),
                'recent_search_histories' => $this->service->getRecentSearchHistories($filters),
                'recent_ratings' => $this->service->getRecentRatings($filters),
                'recent_favorites' => $this->service->getRecentFavorites($filters),
            ],

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            'reports' => $this->service->getReports($filters),

            /*
            |--------------------------------------------------------------------------
            | Rankings
            |--------------------------------------------------------------------------
            */

            'rankings' => [
                'top_searched_places' => $this->service->getTopSearchedPlaces($filters),
                'top_favorite_places' => $this->service->getTopFavoritePlaces($filters),
                'top_rated_places' => $this->service->getTopRatedPlaces($filters),
            ],

            /*
            |--------------------------------------------------------------------------
            | Campus
            |--------------------------------------------------------------------------
            */

            'campus' => [
                'statistics' => $this->service->getCampusStatistics($filters),
            ],

            /*
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            */

            'filters' => $this->service->getFilters(),
        ]);
    }
}
