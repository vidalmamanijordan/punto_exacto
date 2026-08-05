/*
|--------------------------------------------------------------------------
| Entidades Base
|--------------------------------------------------------------------------
*/

export interface DashboardUser {
    id: number;
    name: string;
}

export interface DashboardPlace {
    id: number;
    name: string;
}

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

export interface DashboardStatistics {
    total_campuses: number;
    total_categories: number;
    total_places: number;
    total_faqs: number;
    total_users: number;
    total_ratings: number;
    total_favorites: number;
    total_search_histories: number;
}

export interface DashboardSearchHistory {
    id: number;
    search_text: string;
    created_at: string;
    user: DashboardUser;
    place: DashboardPlace;
}

export interface DashboardRating {
    id: number;
    rating: number;
    comment: string | null;
    created_at: string;
    user: DashboardUser;
    place: DashboardPlace;
}

export interface DashboardFavorite {
    id: number;
    created_at: string;
    user: DashboardUser;
    place: DashboardPlace;
}

export interface DashboardSummary {
    statistics: DashboardStatistics;
    recent_search_histories: DashboardSearchHistory[];
    recent_ratings: DashboardRating[];
    recent_favorites: DashboardFavorite[];
}

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

export interface DashboardChartPoint {
    date: string;
    total: number;
}

export interface DashboardReports {
    search_history: DashboardChartPoint[];
    favorites: DashboardChartPoint[];
    ratings: DashboardChartPoint[];
    users: DashboardChartPoint[];
}

/*
|--------------------------------------------------------------------------
| Rankings
|--------------------------------------------------------------------------
*/

export interface DashboardRanking {
    place_id: number;
    total: number;
    place: DashboardPlace;
}

export interface DashboardRatedRanking {
    place_id: number;
    average_rating: number;
    total_ratings: number;
    place: DashboardPlace;
}

export interface DashboardRankings {
    top_searched_places: DashboardRanking[];
    top_favorite_places: DashboardRanking[];
    top_rated_places: DashboardRatedRanking[];
}

/*
|--------------------------------------------------------------------------
| Campus
|--------------------------------------------------------------------------
*/

export interface DashboardCampusStatistic {
    id: number;
    name: string;
    code: string;
    places: number;
    favorites: number;
    ratings: number;
    searches: number;
}

export interface DashboardCampus {
    statistics: DashboardCampusStatistic[];
}

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

export interface DashboardFilterOption {
    id: number;
    name: string;
}

export interface DashboardPeriodOption {
    value: number;
    label: string;
}

export interface DashboardFilters {
    campuses: DashboardFilterOption[];
    categories: DashboardFilterOption[];
    periods: DashboardPeriodOption[];
}

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

export interface DashboardData {
    summary: DashboardSummary;
    reports: DashboardReports;
    rankings: DashboardRankings;
    campus: DashboardCampus;
    filters: DashboardFilters;
}