<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResource;
use App\Http\Resources\Tours\TourCardResource;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
     public function __construct(
        protected SearchService $searchService
    ) {}

    public function search(SearchRequest $request)
    {
        $tours = $this->searchService->search($request->validated());

        return ApiResponse::success(
            TourCardResource::collection($tours)
        );
    }
}
