import axios from 'axios';

import type {
    KnowledgeBase,
    KnowledgeBaseFormData,
    KnowledgeBaseListResponse,
    KnowledgeBaseResponse,
} from '../types/knowledge-base';

class KnowledgeBaseService {
    private readonly endpoint = '/api/knowledge-base';

    /**
     * Obtiene todos los registros de Knowledge Base.
     */
    async getAll(): Promise<KnowledgeBase[]> {
        const { data } =
            await axios.get<KnowledgeBaseListResponse>(this.endpoint);

        return data.data;
    }

    /**
     * Obtiene un registro específico.
     */
    async getById(id: number): Promise<KnowledgeBase> {
        const { data } = await axios.get<KnowledgeBaseResponse>(
            `${this.endpoint}/${id}`,
        );

        return data.data;
    }

    /**
     * Crea un nuevo registro.
     */
    async create(form: KnowledgeBaseFormData): Promise<KnowledgeBase> {
        const { data } = await axios.post<KnowledgeBaseResponse>(
            this.endpoint,
            form,
        );

        return data.data;
    }

    /**
     * Actualiza un registro existente.
     */
    async update(
        id: number,
        form: KnowledgeBaseFormData,
    ): Promise<KnowledgeBase> {
        const { data } = await axios.put<KnowledgeBaseResponse>(
            `${this.endpoint}/${id}`,
            form,
        );

        return data.data;
    }

    /**
     * Elimina un registro.
     */
    async delete(id: number): Promise<void> {
        await axios.delete(`${this.endpoint}/${id}`);
    }
}

export const knowledgeBaseService = new KnowledgeBaseService();
