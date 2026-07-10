import axios from 'axios';

export const permissionService = {
    async getAll() {
        const response = await axios.get(
            '/api/permissions'
        );

        return response.data;
    },
};