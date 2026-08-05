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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | Helpers de filtros
    |--------------------------------------------------------------------------
    */

    /**
     * Retorna los días del período o null si no aplica.
     */
    private function getPeriodDays(array $filters): ?int
    {
        $period = $filters['period'] ?? null;

        return is_numeric($period) && (int) $period > 0 ? (int) $period : null;
    }

    /**
     * Aplica el filtro de campus (vía relación place) a un Builder.
     *
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    private function applyCampusFilter(Builder $query, array $filters): Builder
    {
        $campus = $filters['campus'] ?? null;

        if ($campus && $campus !== 'all') {
            $query->whereHas('place', fn (Builder $q) => $q->where('campus_id', $campus));
        }

        return $query;
    }

    /**
     * Aplica el filtro de categoría (vía relación place) a un Builder.
     *
     * @param  Builder<Model>  $query
     * @return Builder<Model>
     */
    private function applyCategoryFilter(Builder $query, array $filters): Builder
    {
        $category = $filters['category'] ?? null;

        if ($category && $category !== 'all') {
            $query->whereHas('place', fn (Builder $q) => $q->where('category_id', $category));
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */

    /**
     * Estadísticas generales.
     */
    public function getStatistics(array $filters = []): array
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
     * Últimas búsquedas.
     */
    public function getRecentSearchHistories(array $filters = [], int $limit = 10)
    {
        $query = SearchHistory::with(['user', 'place'])->latest();

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->take($limit)->get();
    }

    /**
     * Últimas valoraciones.
     */
    public function getRecentRatings(array $filters = [], int $limit = 10)
    {
        $query = Rating::with(['user', 'place'])->latest();

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->take($limit)->get();
    }

    /**
     * Últimos favoritos.
     */
    public function getRecentFavorites(array $filters = [], int $limit = 10)
    {
        $query = Favorite::with(['user', 'place'])->latest();

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->take($limit)->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    /**
     * Todos los reportes.
     */
    public function getReports(array $filters = []): array
    {
        return [
            'search_history' => $this->getSearchHistoryChart($filters),
            'favorites' => $this->getFavoritesChart($filters),
            'ratings' => $this->getRatingsChart($filters),
            'users' => $this->getUsersChart($filters),
        ];
    }

    /**
     * Búsquedas por día.
     */
    protected function getSearchHistoryChart(array $filters = [])
    {
        $days = $this->getPeriodDays($filters);

        $query = SearchHistory::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->groupByRaw('DATE(created_at)')->orderBy('date')->get();
    }

    /**
     * Favoritos por día.
     */
    protected function getFavoritesChart(array $filters = [])
    {
        $days = $this->getPeriodDays($filters);

        $query = Favorite::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->groupByRaw('DATE(created_at)')->orderBy('date')->get();
    }

    /**
     * Valoraciones por día.
     */
    protected function getRatingsChart(array $filters = [])
    {
        $days = $this->getPeriodDays($filters);

        $query = Rating::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->groupByRaw('DATE(created_at)')->orderBy('date')->get();
    }

    /**
     * Usuarios registrados por día.
     */
    protected function getUsersChart(array $filters = [])
    {
        $days = $this->getPeriodDays($filters);

        return User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)))
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Rankings
    |--------------------------------------------------------------------------
    */

    /**
     * Lugares más buscados.
     */
    public function getTopSearchedPlaces(array $filters = [], int $limit = 5)
    {
        $days = $this->getPeriodDays($filters);

        $query = SearchHistory::selectRaw('place_id, COUNT(*) as total')
            ->with('place')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->groupBy('place_id')->orderByDesc('total')->take($limit)->get();
    }

    /**
     * Lugares favoritos.
     */
    public function getTopFavoritePlaces(array $filters = [], int $limit = 5)
    {
        $days = $this->getPeriodDays($filters);

        $query = Favorite::selectRaw('place_id, COUNT(*) as total')
            ->with('place')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query->groupBy('place_id')->orderByDesc('total')->take($limit)->get();
    }

    /**
     * Lugares mejor valorados.
     */
    public function getTopRatedPlaces(array $filters = [], int $limit = 5)
    {
        $days = $this->getPeriodDays($filters);

        $query = Rating::selectRaw('place_id, ROUND(AVG(rating),2) as average_rating, COUNT(*) as total_ratings')
            ->with('place')
            ->when($days, fn (Builder $q) => $q->whereDate('created_at', '>=', now()->subDays($days)));

        $this->applyCampusFilter($query, $filters);
        $this->applyCategoryFilter($query, $filters);

        return $query
            ->groupBy('place_id')
            ->havingRaw('COUNT(*) > 0')
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Campus
    |--------------------------------------------------------------------------
    */

    /**
     * Estadísticas por campus mediante un único JOIN para evitar N+1.
     */
    public function getCampusStatistics(array $filters = [])
    {
        $days = $this->getPeriodDays($filters);

        return Campus::select(['campuses.id', 'campuses.name', 'campuses.code'])
            ->selectRaw('COUNT(DISTINCT places.id) as places')
            ->selectRaw('COUNT(DISTINCT favorites.id) as favorites')
            ->selectRaw('COUNT(DISTINCT ratings.id) as ratings')
            ->selectRaw('COUNT(DISTINCT search_histories.id) as searches')
            ->leftJoin('places', 'places.campus_id', '=', 'campuses.id')
            ->leftJoin('favorites', function ($join) use ($days) {
                $join->on('favorites.place_id', '=', 'places.id');
                if ($days) {
                    $join->where('favorites.created_at', '>=', now()->subDays($days));
                }
            })
            ->leftJoin('ratings', function ($join) use ($days) {
                $join->on('ratings.place_id', '=', 'places.id');
                if ($days) {
                    $join->where('ratings.created_at', '>=', now()->subDays($days));
                }
            })
            ->leftJoin('search_histories', function ($join) use ($days) {
                $join->on('search_histories.place_id', '=', 'places.id');
                if ($days) {
                    $join->where('search_histories.created_at', '>=', now()->subDays($days));
                }
            })
            ->groupBy('campuses.id', 'campuses.name', 'campuses.code')
            ->orderBy('campuses.name')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    /**
     * Información para los filtros del Dashboard.
     */
    public function getFilters(): array
    {
        return [

            'campuses' => Campus::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get(),

            'categories' => Category::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get(),

            'periods' => [
                ['value' => 7, 'label' => 'Últimos 7 días'],
                ['value' => 30, 'label' => 'Últimos 30 días'],
                ['value' => 90, 'label' => 'Últimos 90 días'],
                ['value' => 365, 'label' => 'Último año'],
            ],

        ];
    }
}
