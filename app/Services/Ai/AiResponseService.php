<?php

namespace App\Services\Ai;

use App\Http\Resources\FaqResource;
use App\Http\Resources\KnowledgeBaseResource;
use App\Http\Resources\PlaceResource;
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
        | sin importar cuál fue la intención detectada (necesario para
        | el fallback en cascada).
        |
        */

        $place = $places->first();

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
                'place' => $place ? new PlaceResource($place) : null,

                'faqs' => FaqResource::collection($faqs),

                'knowledge_base' => KnowledgeBaseResource::collection($knowledgeBase),

                'intent' => $intent,
            ],
        ];
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
        if ($intent === 'find_place' && $places->isNotEmpty()) {
            return $this->generatePlaceMessage($places);
        }

        if ($intent === 'faq' && $faqs->isNotEmpty()) {
            return $this->generateFaqMessage($faqs);
        }

        if ($intent === 'institutional_information' && $knowledgeBase->isNotEmpty()) {
            return $this->generateKnowledgeMessage($knowledgeBase);
        }

        return $this->generateFallbackMessage(
            $faqs,
            $knowledgeBase,
            $places
        );
    }

    /**
     * Genera una respuesta relacionada con un lugar.
     */
    protected function generatePlaceMessage(Collection $places): string
    {
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

        if ($place->campus && $place->campus->name) {
            $response .= " del campus {$place->campus->name}";
        }

        return $response . '.';
    }

    /**
     * Genera una respuesta basada en una FAQ.
     */
    protected function generateFaqMessage(Collection $faqs): string
    {
        if ($faqs->isEmpty()) {
            return 'No encontré una pregunta frecuente relacionada con tu consulta.';
        }

        return $faqs->first()->answer;
    }

    /**
     * Genera una respuesta basada en Knowledge Base.
     */
    protected function generateKnowledgeMessage(Collection $knowledgeBase): string
    {
        if ($knowledgeBase->isEmpty()) {
            return 'No encontré información institucional relacionada con tu consulta.';
        }

        return $knowledgeBase->first()->content;
    }

    /**
     * Genera una respuesta de respaldo.
     *
     * Prioriza FAQ, luego Knowledge Base, luego Place.
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
            return $this->generatePlaceMessage($places);
        }

        return 'Encontré información relacionada con tu consulta.';
    }
}
