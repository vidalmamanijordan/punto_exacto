export interface Campus {
    id: number;
    name: string;
    code: string;
    address: string | null;
    latitude: number | null;
    longitude: number | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface CampusFormData {
    name: string;
    code: string;
    address: string;
    latitude: number | null;
    longitude: number | null;
    is_active: boolean;
}
