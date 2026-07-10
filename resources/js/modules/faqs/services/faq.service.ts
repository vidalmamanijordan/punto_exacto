import axios from 'axios';
import type { FaqFormData } from '../types/faq';

export const faqService = {

    async getAll() {
        const response = await axios.get(
            '/api/faqs'
        );
        return response.data;
    },

    async create(
        data: FaqFormData
    ) {
        const response = await axios.post(
            '/api/faqs',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: FaqFormData
    ) {
        const response = await axios.put(
            `/api/faqs/${id}`,
            data
        );
        return response.data;
    },

    async delete(
        id: number
    ) {
        const response = await axios.delete(
            `/api/faqs/${id}`
        );
        return response.data;
    },
};