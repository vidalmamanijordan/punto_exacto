import axios from 'axios';
import type { SearchHistoryFormData } from '../types/search-history';

const API_URL = '/api/search-histories';

export const searchHistoryService = {

    async getAll() {
        const response = await axios.get(API_URL);
        return response.data;
    },

    async create(data: SearchHistoryFormData) {
        const response = await axios.post(API_URL, data);
        return response.data;
    },

    async update(id: number, data: SearchHistoryFormData) {
        const response = await axios.put(`${API_URL}/${id}`, data);
        return response.data;
    },

    async delete(id: number) {
        const response = await axios.delete(`${API_URL}/${id}`);
        return response.data;
    },
};
