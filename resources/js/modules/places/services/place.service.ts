import axios from 'axios';
import type {
    PlaceFormData,
} from '../types/place';

export const placeService = {
    async getAll() {
        const response = await axios.get(
            '/api/places'
        );
        return response.data;
    },

    async create(
        data: PlaceFormData
    ) {
        const response = await axios.post(
            '/api/places',
            data
        );
        return response.data;
    },

    async update(
        id: number,
        data: PlaceFormData
    ) {
        const response = await axios.put(
            `/api/places/${id}`,
            data
        );
        return response.data;
    },

    async delete(
        id: number
    ) {
        const response = await axios.delete(
            `/api/places/${id}`
        );
        return response.data;
    },
};