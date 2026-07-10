import axios from 'axios';
import type { CampusFormData } from '../types/campus';

export const campusService = {
    async getAll() {
        const response = await axios.get('/api/campuses');
        return response.data;
    },

    async create(data: CampusFormData) {
        const response = await axios.post(
            '/api/campuses',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: CampusFormData
    ) {
        const response = await axios.put(
            `/api/campuses/${id}`,
            data
        );
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(
            `/api/campuses/${id}`
        );
        return response.data;
    },
};