<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            'Biblioteca' => [
                [
                    'question' => '¿Dónde está la biblioteca?',
                    'answer' => 'La biblioteca se encuentra en el área académica principal del campus.',
                ],
                [
                    'question' => '¿Cuál es el horario de la biblioteca?',
                    'answer' => 'La biblioteca atiende de lunes a sábado de 8:00 AM a 8:00 PM.',
                ],
            ],

            'Laboratorio' => [
                [
                    'question' => '¿Dónde está el laboratorio de redes?',
                    'answer' => 'El laboratorio de redes se encuentra en el bloque de ingeniería.',
                ],
                [
                    'question' => '¿Puedo ingresar libremente al laboratorio?',
                    'answer' => 'El acceso depende del horario académico y autorización correspondiente.',
                ],
            ],

            'Aula' => [
                [
                    'question' => '¿Cómo encuentro mi aula?',
                    'answer' => 'Puedes buscar el aula desde la aplicación Punto Exacto.',
                ],
            ],

            'Oficina' => [
                [
                    'question' => '¿Dónde se encuentra la oficina de admisión?',
                    'answer' => 'La oficina de admisión se encuentra en el edificio administrativo.',
                ],
            ],

            'Auditorio' => [
                [
                    'question' => '¿Dónde está el auditorio principal?',
                    'answer' => 'El auditorio principal se encuentra cerca de la plaza central del campus.',
                ],
            ],

            'Cafetería' => [
                [
                    'question' => '¿Dónde está la cafetería?',
                    'answer' => 'La cafetería está ubicada cerca de las áreas académicas.',
                ],
            ],

        ];

        foreach ($faqs as $categoryName => $questions) {

            $category = Category::where(
                'name',
                $categoryName
            )->first();

            if (!$category) {
                continue;
            }

            foreach ($questions as $index => $faq) {

                Faq::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'question' => $faq['question'],
                    ],
                    [
                        'answer' => $faq['answer'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
