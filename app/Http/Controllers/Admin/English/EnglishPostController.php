<?php

namespace App\Http\Controllers\Admin\English;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnglishSahifasShlulbayt;
use App\Models\EnglishCategory;

class EnglishPostController extends Controller
{
    // Show all posts
    public function index()
    {
        $posts = EnglishSahifasShlulbayt::latest()->paginate(10);
        return view('admin.english.posts.index', compact('posts'));
    }

    // Show create form
    public function create()
    {
        $categories = EnglishCategory::where('post_type', 'sahifas-shlulbayt')->get();
        return view('admin.english.posts.create', compact('categories'));
    }

    // Store new post
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'search_text' => 'nullable|string',
            'redirect_deep_link' => 'nullable|string|max:255',
            'roman_data' => 'nullable|string',
            'sort_number' => 'nullable|integer',

            // Arabic
            'arabic_islrc' => 'nullable|string',
            'arabic_4line' => 'nullable|boolean',
            'arabic_audio_url' => 'nullable|url|max:255',
            'arabic_content' => 'nullable|string',

            // Transliteration
            'transliteration_islrc' => 'nullable|string',
            'transliteration_4line' => 'nullable|boolean',
            'transliteration_audio_url' => 'nullable|url|max:255',
            'transliteration_content' => 'nullable|string',
            'simple_transliteration' => 'nullable|string',

            // Translation
            'translation_islrc' => 'nullable|string',
            'translation_4line' => 'nullable|boolean',
            'translation_audio_url' => 'nullable|url|max:255',
            'translation_content' => 'nullable|string',
            'simple_translation' => 'nullable|string',

            // Next post / internal link
            'next_post_title' => 'nullable|string|max:255',
            'next_post_url' => 'nullable|string|max:255',
            'internal_link' => 'nullable|string|max:255',

            // Categories (JSON)
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:english_categories,id',

            'status' => 'required|in:draft,published,archived',
        ]);

        // Save categories as JSON
        /*if (!empty($data['category_ids'])) {
            $data['category_ids'] = json_encode($data['category_ids']);
        }*/
        $data['category_ids'] = $data['category_ids'] ?? [];
        EnglishSahifasShlulbayt::create($data);

        return redirect()->route('admin.english.post.index')->with('success', 'Post created successfully.');
    }

    // Show edit form
    public function edit(EnglishSahifasShlulbayt $englishPost)
    {
        $categories = EnglishCategory::where('post_type', 'sahifas-shlulbayt')->get();
        return view('admin.english.posts.edit', compact('englishPost', 'categories'));
    }


    // Update post
    public function update(Request $request, EnglishSahifasShlulbayt $englishPost)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'search_text' => 'nullable|string',
            'redirect_deep_link' => 'nullable|string|max:255',
            'roman_data' => 'nullable|string',
            'sort_number' => 'nullable|integer',

            // Arabic
            'arabic_islrc' => 'nullable|string',
            'arabic_4line' => 'nullable|boolean',
            'arabic_audio_url' => 'nullable|url|max:255',
            'arabic_content' => 'nullable|string',

            // Transliteration
            'transliteration_islrc' => 'nullable|string',
            'transliteration_4line' => 'nullable|boolean',
            'transliteration_audio_url' => 'nullable|url|max:255',
            'transliteration_content' => 'nullable|string',
            'simple_transliteration' => 'nullable|string',

            // Translation
            'translation_islrc' => 'nullable|string',
            'translation_4line' => 'nullable|boolean',
            'translation_audio_url' => 'nullable|url|max:255',
            'translation_content' => 'nullable|string',
            'simple_translation' => 'nullable|string',

            // Next post / internal link
            'next_post_title' => 'nullable|string|max:255',
            'next_post_url' => 'nullable|string|max:255',
            'internal_link' => 'nullable|string|max:255',

            // Categories (JSON)
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:english_categories,id',

            'status' => 'required|in:draft,published,archived',
        ]);

        $data['category_ids'] = $data['category_ids'] ?? [];

        $englishPost->update($data);

        return redirect()->route('admin.english.post.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $category = EnglishSahifasShlulbayt::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.english.post.index')->with('success', 'Post deleted successfully.');
    }
}
