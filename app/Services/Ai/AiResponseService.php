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
        | Se muestra siempre que exista un lugar entre los resultados,
        | sin importar cuál fue la intención detectada. Esto es necesario
        | porque el fallback en cascada puede traer un lugar como respaldo
        | aunque la intención original haya sido 'faq' o 'institutional_information'.
        |
        */

        $place = $this->getMainPlace(
            $places
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
     * Se devuelve siempre que haya un lugar entre los resultados,
     * sin depender de la intención detectada.
     */
    protected function getMainPlace(
        Collection $places
    ): mixed {
        $place = $places->first();

        if ($place) {
            unset($place->relevance_score);
        }

        return $place;
    }

    /**
     * Genera el mensaje que verá el usuario.
     *
     * Primero intenta usar la fuente asociada a la intención detectada.
     * Si esa fuente vino vacía (por ejemplo, porque el resultado
     * disponible llegó gracias al fallback en cascada), recurre
     * al mensaje de respaldo basado en lo que sí esté disponible.
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

        if ($intent === 'find_place' && $places->isNotEmpty()) {
            return $this->generatePlaceMessage(
                $places
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Intent: faq
        |--------------------------------------------------------------------------
        */

        if ($intent === 'faq' && $faqs->isNotEmpty()) {
            return $this->generateFaqMessage(
                $faqs
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Intent: institutional_information
        |--------------------------------------------------------------------------
        */

        if ($intent === 'institutional_information' && $knowledgeBase->isNotEmpty()) {
            return $this->generateKnowledgeMessage(
                $knowledgeBase
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Respaldo
        |--------------------------------------------------------------------------
        |
        | Cubre dos casos:
        | 1. Intent desconocido ('general').
        | 2. La fuente esperada por el intent vino vacía, pero el fallback
        |    en cascada encontró algo en otra fuente.
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
     *
     * Prioriza FAQ, luego Knowledge Base, luego Place —
     * usando lo primero que tenga contenido disponible.
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
