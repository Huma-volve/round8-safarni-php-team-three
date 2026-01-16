<?php

namespace App\Services;

use App\Models\Property;

class PropertyService
{
    public function search($request)
    {
        $query = Property::query()
            ->withCount('reviews');

        // 📍 Search by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', '%' . $request->location . '%');
        }

        // 💰 Budget filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 🔀 Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'most_reviewed':
                $query->orderBy('reviews_count', 'desc');
                break;

            default:
                $query->latest();
        }

        return $query->paginate(10);
    }
}
