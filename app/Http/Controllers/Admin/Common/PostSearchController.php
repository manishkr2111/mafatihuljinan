<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostSearchController extends Controller
{
    public function index()
    {
        return view('admin.search-post.search_post');
    }

    public function search(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'title' => 'required|string',
        ]);

        $language = strtolower($request->language);
        $title = $request->title;

        $results = [];

        // Loop through all post types
        foreach (\commonPostTypeOptions() as $postType => $label) {
            $model = \getModelByLanguageAndType($language, $postType);

            if ($model) {
                $queryResults = $model::where('title', 'like', "%$title%")->get();
                if ($queryResults->isNotEmpty()) {
                    $results[$postType] = $queryResults;
                }
            }
        }


        return view('admin.search-post.search_post', compact('results', 'title', 'language'));
    }
}
