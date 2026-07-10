import axios from 'axios';

export const roleService = {
    async getAll() {
        const response = await axios.get('/api/roles');
        return response.data;
    },
};
