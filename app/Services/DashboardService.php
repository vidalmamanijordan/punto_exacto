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
     * Obtiene todas las estadísticas generales del Dashboard.
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
     * Últimas valoraciones.
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
     * Últimos favoritos.
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
}
