<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Category;
use App\Models\Faq;
use App\Models\Favorite;
use App\Models\Place;
use App\Models\Rating;
use App\Models\SearchHistory;
use App\Models\User;

class DashboardService
{
    /**
     * Obtiene las estadísticas generales del Dashboard.
     */
    public function getStatistics(): array
    {
        return [
            'total_campuses' => Campus::count(),
            'total_categories' => Category::count(),
            'total_places' => Place::count(),
            'total_faqs' => Faq::count(),
            'total_users' => User::count(),
            'total_ratings' => Rating::count(),
            'total_favorites' => Favorite::count(),
            'total_search_histories' => SearchHistory::count(),
        ];
    }

    /**
     * Últimas búsquedas realizadas.
     */
    public function getRecentSearchHistories(int $limit = 10)
    {
        return SearchHistory::with([
            'user',
            'place',
        ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Últimas valoraciones realizadas.
     */
    public function getRecentRatings(int $limit = 10)
    {
        return Rating::with([
            'user',
            'place',
        ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Últimos favoritos agregados.
     */
    public function getRecentFavorites(int $limit = 10)
    {
        return Favorite::with([
            'user',
            'place',
        ])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Lugares más buscados.
     */
    public function getTopSearchedPlaces(int $limit = 5)
    {
        return SearchHistory::selectRaw('
                place_id,
                COUNT(*) as total
            ')
            ->with('place')
            ->groupBy('place_id')
            ->orderByDesc('total')
            ->take($limit)
            ->get();
    }

    /**
     * Lugares agregados más veces a favoritos.
     */
    public function getTopFavoritePlaces(int $limit = 5)
    {
        return Favorite::selectRaw('
                place_id,
                COUNT(*) as total
            ')
            ->with('place')
            ->groupBy('place_id')
            ->orderByDesc('total')
            ->take($limit)
            ->get();
    }

    /**
     * Lugares mejor valorados.
     */
    public function getTopRatedPlaces(int $limit = 5)
    {
        return Rating::selectRaw('
                place_id,
                ROUND(AVG(rating),2) as average_rating,
                COUNT(*) as total_ratings
            ')
            ->with('place')
            ->groupBy('place_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get();
    }

    /**
     * Estadísticas por campus.
     */
    public function getCampusStatistics()
    {
        return Campus::withCount([
            'places',
        ])
            ->get()
            ->map(function ($campus) {

                $placeIds = Place::where('campus_id', $campus->id)->pluck('id');

                return [
                    'id' => $campus->id,
                    'name' => $campus->name,
                    'code' => $campus->code,
                    'places' => $campus->places_count,
                    'favorites' => Favorite::whereIn('place_id', $placeIds)->count(),
                    'ratings' => Rating::whereIn('place_id', $placeIds)->count(),
                    'searches' => SearchHistory::whereIn('place_id', $placeIds)->count(),
                ];
            });
    }
}
