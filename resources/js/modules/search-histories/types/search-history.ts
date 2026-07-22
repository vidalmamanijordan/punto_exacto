export interface SearchHistory {
    id: number;
    user: {
        id: number;
        name: string;
    };
    place: {
        id: number;
        name: string;
    };
    search_text: string;
    created_at: string;
    updated_at: string;
}

export interface SearchHistoryFormData {
    user_id: number | null;
    place_id: number | null;
    search_text: string;
}
