<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (Ahmed usually)
        $user = User::where('email', 'ahmed@example.com')->first();
        
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'ahmed@example.com',
                'full_name' => 'أحمد محمد'
            ]);
        }

        // Get some tours
        $tours = Tour::take(2)->get();

        if ($tours->isEmpty()) {
            return;
        }

        foreach ($tours as $tour) {
            // Check if already favorited to avoid duplicates
            if (!$user->favorites()->where('favorable_id', $tour->id)->where('favorable_type', Tour::class)->exists()) {
                Favorite::create([
                    'user_id' => $user->id,
                    'favorable_id' => $tour->id,
                    'favorable_type' => Tour::class,
                ]);
            }
        }
    }
}
