<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Category;
use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener campus por código
        $lima = Campus::where('code', 'LIM')->firstOrFail();
        $juliaca = Campus::where('code', 'JUL')->firstOrFail();
        $tarapoto = Campus::where('code', 'TAR')->firstOrFail();

        // Obtener categorías por nombre
        $biblioteca = Category::where('name', 'Biblioteca')->firstOrFail();
        $laboratorio = Category::where('name', 'Laboratorio')->firstOrFail();

        $places = [
            [
                'campus_id' => $lima->id,
                'category_id' => $biblioteca->id,
                'name' => 'Biblioteca Central',
                'description' => 'La biblioteca central de la universidad, con una amplia colección de libros',
                'building' => 'Edificio A',
                'floor' => '1',
                'room' => '101',
                'image' => 'https://example.com/images/biblioteca.jpg',
                'latitude' => -12.04318,
                'longitude' => -77.02824,
                'is_active' => true,
            ],
            [
                'campus_id' => $lima->id,
                'category_id' => $laboratorio->id,
                'name' => 'Laboratorio de Redes',
                'description' => 'Laboratorio especializado en redes y telecomunicaciones',
                'building' => 'Bloque B',
                'floor' => '2',
                'room' => 'B-204',
                'image' => null,
                'latitude' => -12.04350,
                'longitude' => -77.02840,
                'is_active' => true,
            ],
            [
                'campus_id' => $juliaca->id,
                'category_id' => $biblioteca->id,
                'name' => 'Biblioteca Juliaca',
                'description' => 'Biblioteca principal del campus Juliaca',
                'building' => 'Bloque C',
                'floor' => '1',
                'room' => 'C-101',
                'image' => null,
                'latitude' => -15.50010,
                'longitude' => -70.13350,
                'is_active' => true,
            ],
            [
                'campus_id' => $tarapoto->id,
                'category_id' => $biblioteca->id,
                'name' => 'Biblioteca Tarapoto',
                'description' => 'Biblioteca principal del campus Tarapoto',
                'building' => 'Bloque A',
                'floor' => '1',
                'room' => 'A-102',
                'image' => null,
                'latitude' => -6.48290,
                'longitude' => -76.36450,
                'is_active' => true,
            ],
        ];

        foreach ($places as $place) {
            Place::updateOrCreate(
                ['name' => $place['name']],
                $place
            );
        }
    }
}
