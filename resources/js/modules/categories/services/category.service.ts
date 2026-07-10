import axios from 'axios';
import { CategoryFormData } from '../types/category';

export const categoryService = {
    async getAll() {
        const response = await axios.get('/api/categories');
        return response.data;
    },

    async create(data: CategoryFormData) {
        const response = await axios.post('/api/categories', data);
        return response.data;
    },

    async update(
        id: number,
        data: CategoryFormData
    ) {
        const response = await axios.put(`/api/categories/${id}`, data);
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(`/api/categories/${id}`);
        return response.data;
    }
};
