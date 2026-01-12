<?php

namespace App\Http\Resources\Tours;

use Illuminate\Http\Resources\Json\JsonResource;

class TourCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'type' => $this->type,
            'location' => $this->location,
            'duration' => $this->duration,
            'visit_season' => $this->visit_season,
            'image' => $this->images->first()?->url,
            'rating_average' => round(optional($this->reviews)->avg('rating'), 1),
            'reviews_count' => optional($this->reviews)->count(),
            'price' => $this->price,
            'is_favourite' => auth('sanctum')->check() ? $this->favorites()->where('user_id', auth('sanctum')->id())->exists() : false,
        ];
    }
}
