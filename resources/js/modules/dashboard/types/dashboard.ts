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

export interface DashboardUser {
    id: number;
    name: string;
}

export interface DashboardPlace {
    id: number;
    name: string;
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

export interface DashboardData {
    statistics: DashboardStatistics;
    recent_search_histories: DashboardSearchHistory[];
    recent_ratings: DashboardRating[];
    recent_favorites: DashboardFavorite[];
}
