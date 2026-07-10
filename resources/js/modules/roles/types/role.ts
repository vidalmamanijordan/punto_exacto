import type { Permission } from './permission';

export interface Role {
    id: number;
    name: string;
    guard_name: string;
    permissions: Permission[];
    permissions_count: number;
}

export interface RoleFormData {
    name: string;
    permissions: string[];
}