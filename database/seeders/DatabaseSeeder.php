<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,

            // Base entities
            HotelSeeder::class,
            RoomSeeder::class,
            CarSeeder::class,
            AirportSeeder::class,
            FlightSeeder::class,
            SeatSeeder::class,

            // Tours (moved up because Favorites depend on it)
            TourSeeder::class,

            // Relations / Dependent data
            ImageSeeder::class,
            ReviewSeeder::class,
            FavoriteSeeder::class,
        ]);
    }
}
