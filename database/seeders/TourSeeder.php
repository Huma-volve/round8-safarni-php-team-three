<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Review;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have a user for reviews
        $user = User::first() ?? User::factory()->create([
            'full_name' => 'Reviewer User',
            'email' => 'reviewer@example.com',
        ]);

        $tours = [
            [
                'title' => 'Magical Paris',
                'slug' => 'magical-paris',
                'type' => 'full day tour',
                'location' => 'France, Paris',
                'price' => 1200.00,
                'duration' => 5,
                'visit_season' => 'Spring',
                'activities' => [
                    [
                        'title' => 'Eiffel Tower Visit',
                        'description' => 'Visit the iconic Eiffel Tower and enjoy the view of Paris.',
                    ],
                    [
                        'title' => 'Seine River Cruise',
                        'description' => 'Relax on a cruise along the Seine River.',
                    ],
                    [
                        'title' => 'Louvre Museum',
                        'description' => 'Explore the Louvre and see famous artworks like the Mona Lisa.',
                    ],
                ],
                'recommendation' => 'Perfect for couples and art lovers.',
            ],
            [
                'title' => 'Safari Adventure in Kenya',
                'slug' => 'safari-kenya',
                'type' => 'full day tour',
                'location' => 'Kenya, Masai Mara',
                'price' => 2500.00,
                'duration' => 7,
                'visit_season' => 'Summer',
                'activities' => [
                    [
                        'title' => 'Game Drive',
                        'description' => 'Experience a wildlife game drive in Masai Mara.',
                    ],
                    [
                        'title' => 'Masai Mara Visit',
                        'description' => 'Visit the Masai Mara region and learn about local culture.',
                    ],
                    [
                        'title' => 'Camping',
                        'description' => 'Spend a night camping under the African sky.',
                    ],
                ],
                'recommendation' => 'Bring good binoculars and a camera.',
            ],
            [
                'title' => 'Kyoto Cultural Tour',
                'slug' => 'kyoto-cultural',
                'type' => 'full day tour',
                'location' => 'Japan, Kyoto',
                'price' => 1800.00,
                'duration' => 4,
                'visit_season' => 'Autumn',
                'activities' => [
                    [
                        'title' => 'Tea Ceremony',
                        'description' => 'Participate in a traditional Japanese tea ceremony.',
                    ],
                    [
                        'title' => 'Temple Visits',
                        'description' => 'Visit famous temples and shrines in Kyoto.',
                    ],
                    [
                        'title' => 'Bamboo Forest Walk',
                        'description' => 'Walk through the beautiful Arashiyama bamboo forest.',
                    ],
                ],
                'recommendation' => 'Wear comfortable walking shoes.',
            ],
        ];

        foreach ($tours as $tourData) {
            $tour = Tour::updateOrCreate(
                ['slug' => $tourData['slug']],
                $tourData
            );

            // Add Images
            if (!$tour->images()->exists()) {
                $images = [
                    'magical-paris' => 'https://i.pinimg.com/736x/a4/31/08/a4310874e3916f7ffe580b7fb3175e01.jpg',
                    'safari-kenya' => 'https://i.pinimg.com/736x/a5/55/9f/a5559f11798ba1d3136000119d1bda43.jpg',
                    'kyoto-cultural' => 'https://i.pinimg.com/736x/9d/2a/13/9d2a13cddf643a68f3523c3fc08cf6e5.jpg',
                ];

                $tour->images()->create([
                    'url' => $images[$tour->slug],
                ]);
            }

            // Add Schedule
            if (!$tour->schedules()->exists()) {
                TourSchedule::create([
                    'tour_id' => $tour->id,
                    'start_date' => now()->addDays(10),
                    'capacity' => 20,
                    'available_slots' => 20,
                ]);
                TourSchedule::create([
                    'tour_id' => $tour->id,
                    'start_date' => now()->addDays(20),
                    'capacity' => 15,
                    'available_slots' => 15,
                ]);
            }

            // Add Review
            if (!$tour->reviews()->exists()) {
                $tour->reviews()->create([
                    'user_id' => $user->id,
                    'comment' => 'Great tour!',
                    'rating' => rand(4, 5),
                    'status' => 'approved',
                ]);
            }
        }
    }
}
