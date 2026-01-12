<?php

namespace App\Http\Resources\Tours;

use Illuminate\Http\Resources\Json\JsonResource;

class TourDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'duration' => $this->duration,
            'visit_season' => $this->visit_season,
            'activities' => $this->activities,
            'recommendation' => $this->recommendation,
            'images' => $this->images->pluck('url'),
            'reviews' => $this->reviews->map(function ($review) {
                return [
                    'user' => optional($review->user)->full_name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at,
                ];
            }),
            'rating_average' => round(optional($this->reviews)->avg('rating'), 1),
            'reviews_count' => optional($this->reviews)->count(),
            'schedules' => $this->schedules,
            'price' => $this->price,
            'is_favourite' => auth('sanctum')->check() ? $this->favorites()->where('user_id', auth('sanctum')->id())->exists() : false,
        ];
    }
}

