<?php

namespace App\Services\Ai;

use App\Models\Faq;
use App\Models\KnowledgeBase;
use App\Models\Place;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AiSearchService
{
    /**
     * Palabras que no aportan información relevante
     * para las búsquedas.
     */
    protected array $stopWords = [
        'a',
        'al',
        'con',
        'como',
        'cual',
        'cuales',
        'de',
        'del',
        'donde',
        'el',
        'en',
        'es',
        'esta',
        'este',
        'hay',
        'la',
        'las',
        'lo',
        'los',
        'me',
        'mi',
        'para',
        'por',
        'que',
        'se',
        'un',
        'una',
        'y',
        'yo',

        // Palabras frecuentes pero poco útiles
        // para identificar el contenido.
        'ubicado',
        'ubicada',
        'encuentra',
        'encuentro',
        'puedo',
        'puede',
        'dime',
        'indica',
        'decirme',
        'quisiera',
        'saber',
    ];

    /**
     * Extrae los términos relevantes de una consulta.
     *
     * Este método:
     *
     * 1. Convierte el texto a minúsculas.
     * 2. Elimina acentos.
     * 3. Elimina caracteres especiales.
     * 4. Divide la consulta en palabras.
     * 5. Elimina stop words.
     * 6. Elimina palabras demasiado cortas.
     * 7. Elimina términos duplicados.
     */
    public function extractSearchTerms(string $message): array
    {
        /*
        |--------------------------------------------------------------------------
        | Normalizar texto
        |--------------------------------------------------------------------------
        */

        $normalized = Str::lower(
            Str::ascii($message)
        );

        /*
        |--------------------------------------------------------------------------
        | Eliminar caracteres especiales
        |--------------------------------------------------------------------------
        */

        $normalized = preg_replace(
            '/[^a-z0-9\s]/',
            ' ',
            $normalized
        );

        /*
        |--------------------------------------------------------------------------
        | Separar palabras
        |--------------------------------------------------------------------------
        */

        $words = preg_split(
            '/\s+/',
            trim($normalized)
        );

        /*
        |--------------------------------------------------------------------------
        | Filtrar términos irrelevantes
        |--------------------------------------------------------------------------
        */

        $words = array_filter(
            $words,
            function ($word) {
                return strlen($word) >= 3
                    && !in_array(
                        $word,
                        $this->stopWords,
                        true
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Eliminar duplicados
        |--------------------------------------------------------------------------
        */

        return array_values(
            array_unique($words)
        );
    }

    /**
     * Busca lugares relacionados con los términos proporcionados.
     */
    public function searchPlaces(
        array $terms,
        ?int $campusId = null
    ): Collection {
        $query = Place::query()
            ->with([
                'campus',
                'category',
            ])
            ->where('is_active', true)

            ->when(
                $campusId !== null,
                function ($query) use ($campusId) {
                    $query->where(
                        'campus_id',
                        $campusId
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Buscar coincidencias
        |--------------------------------------------------------------------------
        */

        $query->where(function ($query) use ($terms) {
            foreach ($terms as $term) {
                $query
                    ->orWhere(
                        'name',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'description',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'building',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'floor',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'room',
                        'ILIKE',
                        "%{$term}%"
                    );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Obtener resultados
        |--------------------------------------------------------------------------
        */

        $places = $query
            ->limit(20)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ordenar por relevancia
        |--------------------------------------------------------------------------
        */

        return $this->rankPlaces(
            $places,
            $terms
        );
    }

    /**
     * Busca coincidencias en las preguntas frecuentes.
     */
    public function searchFaqs(
        array $terms
    ): Collection {
        $query = Faq::query()
            ->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Buscar coincidencias
        |--------------------------------------------------------------------------
        */

        $query->where(function ($query) use ($terms) {
            foreach ($terms as $term) {
                $query
                    ->orWhere(
                        'question',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'answer',
                        'ILIKE',
                        "%{$term}%"
                    );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Obtener resultados
        |--------------------------------------------------------------------------
        */

        $faqs = $query
            ->with('category')
            ->limit(20)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ordenar por relevancia
        |--------------------------------------------------------------------------
        */

        return $this->rankFaqs(
            $faqs,
            $terms
        );
    }

    /**
     * Busca coincidencias en la base de conocimiento.
     */
    public function searchKnowledgeBase(
        array $terms
    ): Collection {
        $query = KnowledgeBase::query()
            ->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Buscar coincidencias
        |--------------------------------------------------------------------------
        */

        $query->where(function ($query) use ($terms) {
            foreach ($terms as $term) {
                $query
                    ->orWhere(
                        'title',
                        'ILIKE',
                        "%{$term}%"
                    )
                    ->orWhere(
                        'content',
                        'ILIKE',
                        "%{$term}%"
                    );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Obtener resultados
        |--------------------------------------------------------------------------
        */

        $knowledgeBase = $query
            ->limit(20)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ordenar por relevancia
        |--------------------------------------------------------------------------
        */

        return $this->rankKnowledgeBase(
            $knowledgeBase,
            $terms
        );
    }

    /**
     * Ordena las FAQs según relevancia.
     */
    protected function rankFaqs(
        Collection $faqs,
        array $terms
    ): Collection {
        return $faqs
            ->map(function ($faq) use ($terms) {

                $score = 0;

                $question = Str::lower(
                    Str::ascii($faq->question)
                );

                $answer = Str::lower(
                    Str::ascii($faq->answer)
                );

                foreach ($terms as $term) {

                    /*
                    |------------------------------------------------------------------
                    | Coincidencia en la pregunta
                    |------------------------------------------------------------------
                    */

                    if (str_contains($question, $term)) {
                        $score += 5;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Coincidencia en la respuesta
                    |------------------------------------------------------------------
                    */

                    if (str_contains($answer, $term)) {
                        $score += 2;
                    }
                }

                $faq->relevance_score = $score;

                return $faq;
            })
            ->sortByDesc('relevance_score')
            ->values();
    }

    /**
     * Ordena la base de conocimiento según relevancia.
     */
    protected function rankKnowledgeBase(
        Collection $knowledgeBase,
        array $terms
    ): Collection {
        return $knowledgeBase
            ->map(function ($item) use ($terms) {

                $score = 0;

                $title = Str::lower(
                    Str::ascii($item->title)
                );

                $content = Str::lower(
                    Str::ascii($item->content)
                );

                foreach ($terms as $term) {

                    /*
                    |------------------------------------------------------------------
                    | Coincidencia en el título
                    |------------------------------------------------------------------
                    */

                    if (str_contains($title, $term)) {
                        $score += 5;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Coincidencia en el contenido
                    |------------------------------------------------------------------
                    */

                    if (str_contains($content, $term)) {
                        $score += 2;
                    }
                }

                $item->relevance_score = $score;

                return $item;
            })
            ->sortByDesc('relevance_score')
            ->values();
    }

    /**
     * Ordena los lugares según relevancia.
     */
    protected function rankPlaces(
        Collection $places,
        array $terms
    ): Collection {
        return $places
            ->map(function ($place) use ($terms) {

                $score = 0;

                $name = Str::lower(
                    Str::ascii($place->name)
                );

                $description = Str::lower(
                    Str::ascii(
                        $place->description ?? ''
                    )
                );

                $building = Str::lower(
                    Str::ascii(
                        $place->building ?? ''
                    )
                );

                $floor = Str::lower(
                    Str::ascii(
                        (string) ($place->floor ?? '')
                    )
                );

                $room = Str::lower(
                    Str::ascii(
                        $place->room ?? ''
                    )
                );

                foreach ($terms as $term) {

                    /*
                    |------------------------------------------------------------------
                    | Nombre del lugar
                    |------------------------------------------------------------------
                    */

                    if (str_contains($name, $term)) {
                        $score += 10;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Edificio
                    |------------------------------------------------------------------
                    */

                    if (str_contains($building, $term)) {
                        $score += 5;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Sala
                    |------------------------------------------------------------------
                    */

                    if (str_contains($room, $term)) {
                        $score += 5;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Piso
                    |------------------------------------------------------------------
                    */

                    if (str_contains($floor, $term)) {
                        $score += 3;
                    }

                    /*
                    |------------------------------------------------------------------
                    | Descripción
                    |------------------------------------------------------------------
                    */

                    if (str_contains($description, $term)) {
                        $score += 2;
                    }
                }

                $place->relevance_score = $score;

                return $place;
            })
            ->sortByDesc('relevance_score')
            ->values();
    }
}
