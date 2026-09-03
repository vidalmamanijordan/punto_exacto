<?php

namespace App\Services\Ai;

use Illuminate\Support\Str;

class AiIntentDetectorService
{
    /**
     * Detecta la intención principal de una consulta.
     *
     * Actualmente trabaja sin IA externa.
     * La clasificación se realiza mediante reglas.
     */
    public function detect(string $message): string
    {
        $message = $this->normalize($message);

        /*
        |--------------------------------------------------------------------------
        | 1. Buscar un lugar
        |--------------------------------------------------------------------------
        */

        if ($this->isFindPlaceIntent($message)) {
            return 'find_place';
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Preguntas frecuentes
        |--------------------------------------------------------------------------
        */

        if ($this->isFaqIntent($message)) {
            return 'faq';
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Información institucional
        |--------------------------------------------------------------------------
        */

        if ($this->isInstitutionalInformationIntent($message)) {
            return 'institutional_information';
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Intención general
        |--------------------------------------------------------------------------
        */

        return 'general';
    }

    /**
     * Determina si la consulta corresponde a una búsqueda de lugar.
     */
    protected function isFindPlaceIntent(string $message): bool
    {
        return $this->matches($message, [
            'donde',
            'ubicacion',
            'ubicado',
            'ubicada',
            'encuentra',
            'queda',
            'como llego',
            'localizo',
            'localizar',
        ]);
    }

    /**
     * Determina si la consulta corresponde a una pregunta frecuente.
     */
    protected function isFaqIntent(string $message): bool
    {
        return $this->matches($message, [
            'puedo',
            'puede',
            'permitido',
            'permite',
            'requisito',
            'requisitos',
            'horario',
            'horarios',
            'acceso',
            'ingresar',
            'entrar',
        ]);
    }

    /**
     * Determina si la consulta corresponde a información institucional.
     */
    protected function isInstitutionalInformationIntent(
        string $message
    ): bool {
        return $this->matches($message, [
            'universidad',
            'servicios',
            'servicio',
            'reglamento',
            'reglamentos',
            'politica',
            'politicas',
            'informacion',
            'informacion institucional',
        ]);
    }

    /**
     * Normaliza la consulta.
     */
    protected function normalize(string $message): string
    {
        /*
        |--------------------------------------------------------------------------
        | Minúsculas y eliminación de acentos
        |--------------------------------------------------------------------------
        */

        $message = Str::lower(
            Str::ascii($message)
        );

        /*
        |--------------------------------------------------------------------------
        | Eliminar caracteres especiales
        |--------------------------------------------------------------------------
        */

        $message = preg_replace(
            '/[^a-z0-9\s]/',
            ' ',
            $message
        );

        /*
        |--------------------------------------------------------------------------
        | Normalizar espacios
        |--------------------------------------------------------------------------
        */

        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                $message
            )
        );
    }

    /**
     * Comprueba si la consulta contiene alguno
     * de los patrones indicados.
     */
    protected function matches(
        string $message,
        array $patterns
    ): bool {
        foreach ($patterns as $pattern) {
            if (str_contains($message, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
