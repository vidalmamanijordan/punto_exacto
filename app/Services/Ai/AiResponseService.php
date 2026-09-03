<?php

namespace App\Services\Ai;

use Illuminate\Support\Collection;

class AiResponseService
{
    /**
     * Construye la respuesta final del asistente.
     */
    public function build(
        Collection $faqs,
        Collection $knowledgeBase,
        Collection $places,
        string $intent
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Sin resultados
        |--------------------------------------------------------------------------
        */

        if (
            $faqs->isEmpty() &&
            $knowledgeBase->isEmpty() &&
            $places->isEmpty()
        ) {
            return [
                'success' => false,
                'message' => 'No encontré información oficial relacionada con tu consulta.',
                'data' => [
                    'place' => null,
                    'faqs' => [],
                    'knowledge_base' => [],
                    'intent' => $intent,
                ],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Lugar principal
        |--------------------------------------------------------------------------
        |
        | Solamente utilizamos un lugar principal cuando la intención
        | corresponde a una búsqueda de lugares.
        |
        */

        $place = $this->getMainPlace(
            $places,
            $intent
        );

        /*
        |--------------------------------------------------------------------------
        | Respuesta final
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,

            'message' => $this->generateMessage(
                $faqs,
                $knowledgeBase,
                $places,
                $intent
            ),

            'data' => [
                'place' => $place,

                'faqs' => $this->cleanFaqResults(
                    $faqs
                ),

                'knowledge_base' => $this->cleanKnowledgeResults(
                    $knowledgeBase
                ),

                'intent' => $intent,
            ],
        ];
    }

    /**
     * Obtiene el lugar principal de la respuesta.
     *
     * Un lugar solamente se considera principal cuando
     * la intención detectada es find_place.
     */
    protected function getMainPlace(
        Collection $places,
        string $intent
    ): mixed {
        if ($intent !== 'find_place') {
            return null;
        }

        $place = $places->first();

        if ($place) {
            unset($place->relevance_score);
        }

        return $place;
    }

    /**
     * Genera el mensaje que verá el usuario.
     */
    protected function generateMessage(
        Collection $faqs,
        Collection $knowledgeBase,
        Collection $places,
        string $intent
    ): string {
        /*
        |--------------------------------------------------------------------------
        | Intent: find_place
        |--------------------------------------------------------------------------
        */

        if ($intent === 'find_place') {
            return $this->generatePlaceMessage(
                $places
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Intent: faq
        |--------------------------------------------------------------------------
        */

        if ($intent === 'faq') {
            return $this->generateFaqMessage(
                $faqs
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Intent: institutional_information
        |--------------------------------------------------------------------------
        */

        if ($intent === 'institutional_information') {
            return $this->generateKnowledgeMessage(
                $knowledgeBase
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Intent desconocido
        |--------------------------------------------------------------------------
        |
        | Utilizamos una respuesta de respaldo.
        |
        */

        return $this->generateFallbackMessage(
            $faqs,
            $knowledgeBase,
            $places
        );
    }

    /**
     * Genera una respuesta relacionada con un lugar.
     */
    protected function generatePlaceMessage(
        Collection $places
    ): string {
        if ($places->isEmpty()) {
            return 'No encontré un lugar relacionado con tu consulta.';
        }

        $place = $places->first();

        $response = $place->name;

        if ($place->building) {
            $response .= " se encuentra en {$place->building}";
        }

        if ($place->floor !== null) {
            $response .= ", piso {$place->floor}";
        }

        if ($place->room) {
            $response .= ", sala {$place->room}";
        }

        if (
            $place->campus &&
            $place->campus->name
        ) {
            $response .= " del campus {$place->campus->name}";
        }

        return $response . '.';
    }

    /**
     * Genera una respuesta basada en una FAQ.
     */
    protected function generateFaqMessage(
        Collection $faqs
    ): string {
        if ($faqs->isEmpty()) {
            return 'No encontré una pregunta frecuente relacionada con tu consulta.';
        }

        return $faqs->first()->answer;
    }

    /**
     * Genera una respuesta basada en Knowledge Base.
     */
    protected function generateKnowledgeMessage(
        Collection $knowledgeBase
    ): string {
        if ($knowledgeBase->isEmpty()) {
            return 'No encontré información institucional relacionada con tu consulta.';
        }

        return $knowledgeBase->first()->content;
    }

    /**
     * Genera una respuesta de respaldo.
     */
    protected function generateFallbackMessage(
        Collection $faqs,
        Collection $knowledgeBase,
        Collection $places
    ): string {
        if ($faqs->isNotEmpty()) {
            return $faqs->first()->answer;
        }

        if ($knowledgeBase->isNotEmpty()) {
            return $knowledgeBase->first()->content;
        }

        if ($places->isNotEmpty()) {
            return $this->generatePlaceMessage(
                $places
            );
        }

        return 'Encontré información relacionada con tu consulta.';
    }

    /**
     * Limpia información interna de las FAQs.
     */
    protected function cleanFaqResults(
        Collection $faqs
    ): Collection {
        return $faqs
            ->map(function ($faq) {
                unset($faq->relevance_score);

                return $faq;
            })
            ->values();
    }

    /**
     * Limpia información interna de Knowledge Base.
     */
    protected function cleanKnowledgeResults(
        Collection $knowledgeBase
    ): Collection {
        return $knowledgeBase
            ->map(function ($item) {
                unset($item->relevance_score);

                return $item;
            })
            ->values();
    }
}
