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


    public function showSearchReplace()
    {
        return view('admin.search-post.search_replace_post');
    }

    public function searchReplace(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'search_text' => 'required|string',
            'replace_text' => 'nullable|string',
        ]);

        $language = strtolower($request->language);
        $search   = $request->search_text;
        $replace  = $request->replace_text;

        $results = [];

        // Columns you want to search inside
        $searchableFields = [
            'arabic_content',
            'simple_arabic',
            'transliteration_content',
            'simple_transliteration',
            'simple_translation',
            'translation_content',
        ];

        foreach (\commonPostTypeOptions() as $postType => $label) {

            $model = \getModelByLanguageAndType($language, $postType);

            if ($model) {

                // Fetch posts where ANY target column contains search text
                $posts = $model::where(function ($q) use ($searchableFields, $search) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'like', "%$search%");
                    }
                })->get();

                if ($posts->isNotEmpty()) {

                    // Prepare matched data list
                    foreach ($posts as $post) {
                        $matches = [];

                        foreach ($searchableFields as $field) {
                            if (!empty($post->$field) && str_contains($post->$field, $search)) {
                                // Add the matched part
                                $matches[$field] = $post->$field;
                            }
                        }

                        if (!empty($matches)) {
                            $results[$postType][] = [
                                'post'    => $post,
                                'matches' => $matches,
                            ];
                        }
                    }
                }
            }
        }

        return view('admin.search-post.search_replace_post', compact(
            'results',
            'search',
            'replace',
            'language'
        ));
    }

    public function performReplace(Request $request)
    {
        $request->validate([
            'language'     => 'required|string',
            'search_text'   => 'required|string',
            'replace_text'  => 'required|string',
            'post_type'     => 'required|string',
        ]);

        $language = strtolower($request->language);
        $search   = $request->search_text;
        $replace  = $request->replace_text;
        $postType = $request->post_type;

        $model = \getModelByLanguageAndType($language, $postType);

        if (!$model) {
            return back()->with('error', 'Invalid post type.');
        }

        // Columns where replacement will occur
        $replaceableFields = [
            'arabic_content',
            'simple_arabic',
            'transliteration_content',
            'simple_transliteration',
            'simple_translation',
            'translation_content',
        ];

        // Get posts where ANY of these columns contains the search text
        $posts = $model::where(function ($q) use ($replaceableFields, $search) {
            foreach ($replaceableFields as $column) {
                $q->orWhere($column, 'like', "%$search%");
            }
        })->get();

        $updatedCount = 0;

        foreach ($posts as $post) {

            $changed = false;

            foreach ($replaceableFields as $column) {

                if (!empty($post->$column) && str_contains($post->$column, $search)) {
                    $post->$column = str_replace($search, $replace, $post->$column);
                    $changed = true;
                }
            }

            if ($changed) {
                $post->save();
                $updatedCount++;
            }
        }

        return back()->with('success', "Replacement completed in {$updatedCount} posts!");
    }
}
