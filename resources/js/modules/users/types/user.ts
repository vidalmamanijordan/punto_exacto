export interface User {
    id: number;
    name: string;
    email: string;
    roles: { id: number; name: string }[];
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface UserFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    role: string;
    is_active: boolean;
}
