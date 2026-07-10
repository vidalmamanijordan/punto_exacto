export interface Faq {
    id: number;
    category_id: number;
    question: string;
    answer: string;
    is_active: boolean;
    category: {
        id: number;
        name: string;
    };
    created_at: string;
    updated_at: string;
}

export interface FaqFormData {
    category_id: number | null;
    question: string;
    answer: string;
    is_active: boolean;
}
