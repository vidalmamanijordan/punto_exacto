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

    /**
     * Obtiene toda la información del Dashboard.
     */
    public function index(): JsonResponse
    {
        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | Estadísticas generales
            |--------------------------------------------------------------------------
            */
            'statistics' => $this->service->getStatistics(),
            /*
            |--------------------------------------------------------------------------
            | Actividad reciente
            |--------------------------------------------------------------------------
            */
            'recent_search_histories' => $this->service->getRecentSearchHistories(),
            'recent_ratings' => $this->service->getRecentRatings(),
            'recent_favorites' => $this->service->getRecentFavorites(),
            /*
            |--------------------------------------------------------------------------
            | Rankings
            |--------------------------------------------------------------------------
            */
            'top_searched_places' => $this->service->getTopSearchedPlaces(),
            'top_favorite_places' => $this->service->getTopFavoritePlaces(),
            'top_rated_places' => $this->service->getTopRatedPlaces(),
            /*
            |--------------------------------------------------------------------------
            | Estadísticas por Campus
            |--------------------------------------------------------------------------
            */
            'campus_statistics' => $this->service->getCampusStatistics(),
        ]);
    }
}
