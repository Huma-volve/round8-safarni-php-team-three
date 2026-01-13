<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
     protected $model = Image::class;

    public function definition()
    
    {
    
        $type = $this->faker->randomElement([
    
            Hotel::class,
    
            Room::class,
    ]);

    if ($type === Hotel::class) {

        $hotel = Hotel::inRandomOrder()->first();

        if (! $hotel) {
    
            return [];
    
        }

        return [
    
            'url' => $this->faker->randomElement([
    
                'https://picsum.photos/id/1015/640/480',
    
                'https://picsum.photos/id/1016/640/480',
    
                'https://picsum.photos/id/1020/640/480',
    
                'https://picsum.photos/id/1024/640/480',
    
                'https://picsum.photos/id/1027/640/480',
            ]),
    
            'imageable_id' => $hotel->id,
    
            'imageable_type' => Hotel::class,
        ];
    
    }

   
    $room = Room::where('is_available', true)
    
    ->inRandomOrder()
    
    ->first();

    if (! $room) {
    
        return [];
    }

    return [
    
        'url' => $this->faker->randomElement([
    
            'https://picsum.photos/id/1035/640/480',
    
            'https://picsum.photos/id/1036/640/480',
    
            'https://picsum.photos/id/1040/640/480',
    
            'https://picsum.photos/id/1044/640/480',
    
            'https://picsum.photos/id/1047/640/480',
        ]),
    
        'imageable_id' => $room->id,
    
        'imageable_type' => Room::class,
    ];
}

}
