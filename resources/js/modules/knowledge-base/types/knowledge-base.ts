/*
|--------------------------------------------------------------------------
| Knowledge Base
|--------------------------------------------------------------------------
*/

export interface KnowledgeBase {
    id: number;
    title: string;
    content: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

/*
|--------------------------------------------------------------------------
| Formulario
|--------------------------------------------------------------------------
*/

export interface KnowledgeBaseFormData {
    title: string;
    content: string;
    is_active: boolean;
}

/*
|--------------------------------------------------------------------------
| Respuesta API
|--------------------------------------------------------------------------
*/

export interface KnowledgeBaseResponse {
    data: KnowledgeBase;
}

export interface KnowledgeBaseListResponse {
    data: KnowledgeBase[];
}

/*
|--------------------------------------------------------------------------
| Errores de validación
|--------------------------------------------------------------------------
*/

export interface KnowledgeBaseFormErrors {
    title?: string;
    content?: string;
    is_active?: string;
}
