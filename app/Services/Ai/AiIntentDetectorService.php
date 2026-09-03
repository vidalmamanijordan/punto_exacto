<?php

namespace App\Services\Ai;

use Illuminate\Support\Str;

class AiIntentDetectorService
{
    /**
     * Detecta la intención principal de una consulta.
     *
     * Sistema de puntaje: cuenta cuántas palabras clave de cada
     * categoría aparecen en el mensaje y elige la de mayor puntaje.
     * En caso de empate, prioriza find_place > faq > institutional_information
     * (el orden en que se definen en el array $scores).
     */
    public function detect(string $message): string
    {
        $message = $this->normalize($message);

        $scores = [
            'find_place' => $this->countMatches($message, $this->findPlacePatterns()),
            'faq' => $this->countMatches($message, $this->faqPatterns()),
            'institutional_information' => $this->countMatches($message, $this->institutionalInformationPatterns()),
        ];

        arsort($scores);

        $topIntent = array_key_first($scores);

        return $scores[$topIntent] > 0 ? $topIntent : 'general';
    }

    /**
     * Palabras clave asociadas a la búsqueda de un lugar.
     *
     * Se usan raíces (stems) en vez de formas completas cuando es posible,
     * para evitar contar dos veces la misma idea (ej. 'ubica' cubre
     * 'ubicacion', 'ubicado' y 'ubicada' en una sola coincidencia).
     */
    protected function findPlacePatterns(): array
    {
        return [
            'donde',
            'ubica',
            'encuentra',
            'queda',
            'como llego',
            'localiz',
        ];
    }

    /**
     * Palabras clave asociadas a preguntas frecuentes.
     */
    protected function faqPatterns(): array
    {
        return [
            'pued',
            'permit',
            'requisit',
            'horario',
            'acceso',
            'ingres',
            'entra',
        ];
    }

    /**
     * Palabras clave asociadas a información institucional.
     */
    protected function institutionalInformationPatterns(): array
    {
        return [
            'universidad',
            'servici',
            'reglamento',
            'politica',
            'informacion',
        ];
    }

    /**
     * Normaliza la consulta.
     */
    protected function normalize(string $message): string
    {
        $message = Str::lower(Str::ascii($message));

        $message = preg_replace('/[^a-z0-9\s]/', ' ', $message);

        return trim(preg_replace('/\s+/', ' ', $message));
    }

    /**
     * Cuenta cuántos patrones de la lista aparecen en el mensaje.
     */
    protected function countMatches(string $message, array $patterns): int
    {
        $count = 0;

        foreach ($patterns as $pattern) {
            if (str_contains($message, $pattern)) {
                $count++;
            }
        }

        return $count;
    }
}
