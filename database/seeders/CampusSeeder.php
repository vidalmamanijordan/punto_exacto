<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        Campus::insert([
            [
                'name' => 'Lima',
                'code' => 'LIM',
                'address' => 'Av. Universitaria 1801, San Miguel, Lima',
                'latitude' => -12.04318,
                'longitude' => -77.02824,
                'is_active' => true,
            ],
            [
                'name' => 'Juliaca',
                'code' => 'JUL',
                'address' => 'Av. Circunvalación 123, Juliaca, Puno',
                'latitude' => -15.50000,
                'longitude' => -70.13333,
                'is_active' => true,
            ],
            [
                'name' => 'Tarapoto',
                'code' => 'TAR',
                'address' => 'Av. Circunvalación 456, Tarapoto, San Martín',
                'latitude' => -6.48278,
                'longitude' => -76.36444,
                'is_active' => true,
            ]
        ]);
    }
}
