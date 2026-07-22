import axios from 'axios';

export const userService = {

    async getAll() {
        const response = await axios.get('/api/users');
        return response.data;
    },

};
