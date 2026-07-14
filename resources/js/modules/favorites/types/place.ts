export interface Place {
    id: number;
    campus_id: number;
    category_id: number;
    name: string;
    description: string | null;
    building: string | null;
    floor: string | null;
    reference: string | null;
    latitude: number;
    longitude: number;
    image: string | null;
    is_active: boolean;
}
