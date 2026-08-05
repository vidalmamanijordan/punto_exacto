import axios from 'axios';
import type { DashboardData } from '../types/dashboard';

/*
|--------------------------------------------------------------------------
| Dashboard Filters
|--------------------------------------------------------------------------
*/

export interface DashboardFilters {
    period: string;
    campus: string | number;
    category: string | number;
}

class DashboardService {

    /**
     * Obtiene toda la información del Dashboard.
     */
    async getDashboard(
        filters?: Partial<DashboardFilters>,
    ): Promise<DashboardData> {
        try {

            const { data } = await axios.get<DashboardData>(
                '/api/dashboard',
                {
                    params: filters,
                },
            );

            return data;

        } catch (error) {
            console.error(
                'Error al obtener la información del Dashboard.',
                error,
            );

            throw error;
        }
    }
}

export const dashboardService = new DashboardService();