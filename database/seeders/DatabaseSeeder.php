<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            //Seguridad
            RoleSeeder::class,
            PermissionSeeder::class,
            //Usuarios
            AdminUserSeeder::class,
            UserSeeder::class,
            //Catálogos
            CampusSeeder::class,
            CategorySeeder::class,
            //Informacion principal
            PlaceSeeder::class,
            FaqSeeder::class,
            KnowledgeBaseSeeder::class,

            //Actividad de usuarios
            RatingSeeder::class,
            FavoriteSeeder::class,
            SearchHistorySeeder::class,
        ]);
    }
}
