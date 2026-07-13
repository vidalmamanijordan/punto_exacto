<?php

namespace Database\Seeders;

use App\Models\Place;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $places = Place::all();

        if ($users->isEmpty() || $places->isEmpty()) {
            $this->command->info('No users or places found. Please seed users and places first.');
            return;
        }

        foreach ($places as $place) {
            $users->random(min(3, $users->count()))->each(
                function ($user) use ($place) {
                    Rating::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'place_id' => $place->id,
                        ],
                        [
                            'rating' => rand(3, 5),
                            'comment' => fake()->optional()->sentence(),
                        ]
                    );
                }
            );
        }
    }
}
