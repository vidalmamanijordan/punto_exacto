<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $places = Place::inRandomOrder()
                ->take(rand(2, 5))
                ->get();

            foreach ($places as $place) {
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'place_id' => $place->id,
                ]);
            }
        }
    }
}
