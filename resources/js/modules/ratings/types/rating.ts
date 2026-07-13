import type { User } from './user';
import type { Place } from './place';

export interface Rating {
    id: number;
    user: User;
    place: Place;
    rating: number;
    comment: string | null;
    created_at: string;
    updated_at: string;
}

export interface RatingFormData {
    user_id: number | null;
    place_id: number | null;
    rating: number;
    comment: string | null;
}
