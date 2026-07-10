export interface Place {
    id: number;
    campus_id: number;
    category_id: number;
    name: string;
    description: string | null;
    building: string | null;
    floor: string | null;
    room: string | null;
    image: string | null;
    latitude: number | null;
    longitude: number | null;
    is_active: boolean;
    campus: {
        id: number;
        name: string;
    };
    category: {
        id: number;
        name: string;
    };
    created_at: string;
    updated_at: string;
}

export interface PlaceFormData {
    campus_id: number | null;
    category_id: number | null;
    name: string;
    description: string;
    building: string;
    floor: string;
    room: string;
    image: string;
    latitude: number | null;
    longitude: number | null;
    is_active: boolean;
}