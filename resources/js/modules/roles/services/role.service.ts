import axios from 'axios';
import type { RoleFormData } from '../types/role';

export const roleService = {
    async getAll() {
        const response = await axios.get('/api/roles');
        return response.data;
    },

    async create(data: RoleFormData) {
        const response = await axios.post(
            '/api/roles',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: RoleFormData
    ) {
        const response = await axios.put(
            `/api/roles/${id}`,
            data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(
            `/api/roles/${id}`
        );
        return response.data;
    },
};