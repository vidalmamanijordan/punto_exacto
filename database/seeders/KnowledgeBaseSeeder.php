<?php

namespace Database\Seeders;

use App\Models\KnowledgeBase;
use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        KnowledgeBase::insert([

            [
                'title' => 'Biblioteca Central',
                'content' => 'La Biblioteca Central se encuentra en el segundo piso del Bloque C. Atiende de lunes a viernes de 08:00 a 20:00 horas y sábados de 08:00 a 13:00 horas.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laboratorio de Redes',
                'content' => 'El Laboratorio de Redes está ubicado en el tercer piso del Bloque B. Está destinado para las prácticas de Ingeniería de Sistemas e Ingeniería de Software.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Mesa de Partes',
                'content' => 'La Mesa de Partes se encuentra en el primer piso del edificio administrativo. Atiende solicitudes académicas y administrativas.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Horario de Atención',
                'content' => 'Las oficinas administrativas atienden de lunes a viernes desde las 08:00 hasta las 17:30 horas.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Servicios Universitarios',
                'content' => 'La universidad cuenta con cafetería, biblioteca, enfermería, laboratorios, auditorios, salas de estudio y áreas deportivas disponibles para los estudiantes.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
