import axios from 'axios';
import type { RatingFormData } from '../types/rating';

export const ratingService = {
    async getAll() {
        const response = await axios.get('/api/ratings');
        return response.data;
    },

    async create(data: RatingFormData) {
        const response = await axios.post(
            '/api/ratings',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: RatingFormData
    ) {
        const response = await axios.put(
            `/api/ratings/${id}`,
            data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(
            `/api/ratings/${id}`
        );
        return response.data;
    },
};
