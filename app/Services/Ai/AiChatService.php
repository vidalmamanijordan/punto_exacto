<?php

namespace App\Services\Ai;

class AiChatService
{
    /**
     * Servicio encargado de detectar la intención
     * de la consulta del usuario.
     */
    protected AiIntentDetectorService $intentDetector;

    /**
     * Servicio encargado de realizar las búsquedas
     * en las fuentes oficiales del sistema.
     */
    protected AiSearchService $searchService;

    /**
     * Servicio encargado de construir la respuesta final.
     */
    protected AiResponseService $responseService;

    /**
     * Inyectamos los servicios necesarios.
     */
    public function __construct(
        AiIntentDetectorService $intentDetector,
        AiSearchService $searchService,
        AiResponseService $responseService
    ) {
        $this->intentDetector = $intentDetector;
        $this->searchService = $searchService;
        $this->responseService = $responseService;
    }

    /**
     * Procesa una consulta del usuario.
     *
     * Flujo:
     *
     * 1. Detectar intención.
     * 2. Extraer términos.
     * 3. Buscar según la intención.
     * 4. Construir la respuesta.
     * 5. Devolver el resultado.
     */
    public function chat(
        string $message,
        ?int $campusId = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | 1. Detectar intención
        |--------------------------------------------------------------------------
        */

        $intent = $this->intentDetector->detect($message);

        /*
        |--------------------------------------------------------------------------
        | 2. Extraer términos de búsqueda
        |--------------------------------------------------------------------------
        */

        $terms = $this->searchService->extractSearchTerms($message);

        if (empty($terms)) {
            return $this->emptyResponse($intent);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Buscar información según la intención
        |--------------------------------------------------------------------------
        */

        $results = $this->searchByIntent(
            $intent,
            $terms,
            $campusId
        );

        /*
        |--------------------------------------------------------------------------
        | 4. Construir respuesta
        |--------------------------------------------------------------------------
        */

        $response = $this->responseService->build(
            $results['faqs'],
            $results['knowledge_base'],
            $results['places'],
            $intent
        );

        /*
        |--------------------------------------------------------------------------
        | 5. Agregar intención
        |--------------------------------------------------------------------------
        */

        $response['data']['intent'] = $intent;

        return $response;
    }

    /**
     * Decide qué fuente de información debe consultarse
     * según la intención detectada.
     */
    protected function searchByIntent(
        string $intent,
        array $terms,
        ?int $campusId = null
    ): array {
        return match ($intent) {

            'find_place' => $this->searchForPlace(
                $terms,
                $campusId
            ),

            'faq' => $this->searchForFaq(
                $terms
            ),

            'institutional_information' => $this->searchForInstitutionalInformation(
                $terms
            ),

            default => $this->searchDefault(
                $terms,
                $campusId
            ),
        };
    }

    /**
     * Busca información relacionada con lugares.
     */
    protected function searchForPlace(
        array $terms,
        ?int $campusId = null
    ): array {
        return [
            'places' => $this->searchService->searchPlaces(
                $terms,
                $campusId
            ),

            'faqs' => collect(),

            'knowledge_base' => collect(),
        ];
    }

    /**
     * Busca preguntas frecuentes.
     */
    protected function searchForFaq(
        array $terms
    ): array {
        return [
            'places' => collect(),

            'faqs' => $this->searchService->searchFaqs(
                $terms
            ),

            'knowledge_base' => collect(),
        ];
    }

    /**
     * Busca información institucional.
     */
    protected function searchForInstitutionalInformation(
        array $terms
    ): array {
        return [
            'places' => collect(),

            'faqs' => collect(),

            'knowledge_base' => $this->searchService->searchKnowledgeBase(
                $terms
            ),
        ];
    }

    /**
     * Búsqueda general cuando no se puede determinar
     * una intención específica.
     */
    protected function searchDefault(
        array $terms,
        ?int $campusId = null
    ): array {
        return [
            'places' => $this->searchService->searchPlaces(
                $terms,
                $campusId
            ),

            'faqs' => $this->searchService->searchFaqs(
                $terms
            ),

            'knowledge_base' => $this->searchService->searchKnowledgeBase(
                $terms
            ),
        ];
    }

    /**
     * Respuesta cuando la consulta no contiene
     * términos útiles para realizar una búsqueda.
     */
    protected function emptyResponse(
        string $intent
    ): array {
        return [
            'success' => false,

            'message' => 'Por favor, escribe una consulta más específica.',

            'data' => [
                'places' => [],
                'faqs' => [],
                'knowledge_base' => [],
                'intent' => $intent,
            ],
        ];
    }
}
