import axios from 'axios';
import type { UserFormData } from '../types/user';

export const userService = {
    async getAll() {
        const response = await axios.get('/api/users');
        return response.data;
    },

    async create(data: UserFormData) {
        const response = await axios.post(
            '/api/users',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: UserFormData
    ) {
        const response = await axios.put(
            `/api/users/${id}`,
            data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(
            `/api/users/${id}`
        );
        return response.data;
    },
};