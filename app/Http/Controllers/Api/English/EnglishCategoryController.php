<?php

namespace App\Http\Controllers\Api\English;

use App\Http\Controllers\Controller;
use App\Models\English\EnglishCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EnglishCategoryController extends Controller
{


    private function hideTimestamps($category)
    {
        $category->makeHidden(['created_at', 'updated_at', 'post_type']);

        if ($category->relationLoaded('allChildren') && $category->allChildren->isNotEmpty()) {
            $category->allChildren->transform(function ($child) {
                return $this->hideTimestamps($child);
            });
        }

        return $category;
    }
    public function index(Request $request)
    {
        $post_type = $request->query('post_type', $request->post_type);
        $cacheKey = 'english_categories_' . $post_type;
        $categories = Cache::rememberForever($cacheKey, function () use ($post_type) {
            $cats = EnglishCategory::whereNull('parent_id')
                ->where('post_type', $post_type)
                ->with('allChildren')
                ->get();

            // Hide timestamps recursively
            return $cats->transform(function ($category) {
                return $this->hideTimestamps($category);
            });
        });
        return response()->json([
            'status' => true,
            'post_type' => $post_type,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }


    public function allDualCategories()
    {
        $cacheKey = 'english_all_categories';
        $cats = Cache::remember($cacheKey, 1440, function () {
            return EnglishCategory::select('id','name', 'post_type', 'parent_id')
                ->orderBy('sort_number', 'asc')
                ->get();
        });

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => $cats
        ]);
    }
}
