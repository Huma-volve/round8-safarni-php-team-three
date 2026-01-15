<?php

namespace App\Services;

use App\Models\Tour;

class SearchService
{
    public function search(array $data)
    {
        $query = Tour::query();

        // 🔍 Search by location
        if (!empty($data['location'])) {
            $query->where('location', 'like', '%' . $data['location'] . '%');
        }

        // 💰 Budget filter
        if (!empty($data['min_price'])) {
            $query->where('price', '>=', $data['min_price']);
        }

        if (!empty($data['max_price'])) {
            $query->where('price', '<=', $data['max_price']);
        }

        return $query->paginate(10);
    }
}
