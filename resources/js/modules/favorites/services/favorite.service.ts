import axios from 'axios';
import type { FavoriteFormData } from '../types/favorite';

export const favoriteService = {
    async getAll() {
        const response = await axios.get('/api/favorites');
        return response.data;
    },

    async create(data: FavoriteFormData) {
        const response = await axios.post('/api/favorites', data
        );
        return response.data;
    },

    async update(
        id: number,
        data: FavoriteFormData
    ) {
        const response = await axios.put(`/api/favorites/${id}`, data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(`/api/favorites/${id}`
        );
        return response.data;
    },
};
