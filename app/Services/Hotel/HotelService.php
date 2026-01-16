<?php

namespace App\Services\Hotel;
use App\Models\Hotel;
use App\Models\Room;

class HotelService {
    
    public function getHotels(?float $lat = null , ?float $lng = null){
            
         $query = Hotel::query()
   
         ->with('images')
   
         ->withAvg('reviews', 'rating')
   
         ->orderByDesc('reviews_avg_rating');

            
        if ($lat && $lng) {
            
            $query->selectRaw("
            
                hotels.*,
            
                ( 6371 * acos(
              
                cos(radians(?)) *
              
                cos(radians(lat)) *
              
                cos(radians(lng) - radians(?)) +
              
                sin(radians(?)) *
              
                sin(radians(lat))
              
                )) AS distance
            
                ", [$lat, $lng, $lat])
            
                ->orderBy('distance');
        }

        return $query->paginate(10);
     }
    public function getHotelDetails(int $id, int $roomsPerPage = 5)
    {
        $hotel = Hotel::with('images')
        
        ->withAvg('reviews', 'rating')
        
        ->findOrFail($id);

       
        $hotel->rooms = $hotel->rooms()

        ->with('images')

        ->where('is_available', true)
              
        ->whereDoesntHave('bookingDetails', function($q) {
        
            $q->whereHas('booking', function($q2) {
        
                $q2->where('booking_status', 'pending'); 
        
            });
        
        })

        ->paginate($roomsPerPage);

        return $hotel;
    }
}
