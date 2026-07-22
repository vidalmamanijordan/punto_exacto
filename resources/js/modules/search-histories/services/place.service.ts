import axios from 'axios';

export const placeService = {

    async getAll() {
        const response = await axios.get('/api/places');
        return response.data;
    },

};
