<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Image;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::factory()
        
        ->count(30)
        
        ->create()
    
        ->each(function ($room) {
    
            Image::factory()->create([
    
                'imageable_id' => $room->id,
    
                'imageable_type' => Room::class,
        ]);
    });;
    }
}

