import axios from 'axios';

import type { DashboardData } from '../types/dashboard';

class DashboardService {

    /**
     * Obtiene toda la información del Dashboard.
     */
    async getDashboard(): Promise<DashboardData> {
        const response = await axios.get<DashboardData>('/api/dashboard');
        return response.data;
    }
}

export const dashboardService = new DashboardService();
