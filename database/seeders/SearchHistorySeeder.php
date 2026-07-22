<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class SearchHistorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $places = Place::all();

        if ($users->isEmpty() || $places->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            SearchHistory::create([
                'user_id' => $user->id,
                'place_id' => $places->random()->id,
                'search_text' => fake()->sentence(2),
            ]);
        }
    }
}
