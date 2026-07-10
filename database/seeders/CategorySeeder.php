<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Aula',
            'Laboratorio',
            'Biblioteca',
            'Oficina',
            'Auditorio',
            'Gimnasio',
            'Cafetería',
            'Sala de conferencias'
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category],
                [
                    'description' => "Descripción para la categoría $category",
                    'is_active' => true
                ],
            );
        }
    }
}
