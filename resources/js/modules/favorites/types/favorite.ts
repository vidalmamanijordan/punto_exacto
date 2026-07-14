import type { User } from './user';
import type { Place } from './place';

export interface Favorite {
    id: number;
    user: User;
    place: Place;
    created_at: string;
    updated_at: string;
}

export interface FavoriteFormData {
    user_id: number | null;
    place_id: number | null;
}