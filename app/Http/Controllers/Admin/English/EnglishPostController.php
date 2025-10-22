<?php

namespace App\Http\Controllers\Admin\English;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnglishCategory;

class EnglishPostController extends Controller
{

    // Show all posts
    public function index(Request $request)
    {
        //dd($request->all());
        $postType = $request->post_type;
        if (!$postType) {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getEnglishModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $posts = $modelClass::latest()->paginate(10);
        return view('admin.english.posts.index', compact('posts', 'postType'));
    }

    // Show create form
    public function create(Request $request)
    {
        $postType = $request->query('post_type');
        if (!$postType) {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getEnglishModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        //$categories = EnglishCategory::where('post_type', 'sahifas-shlulbayt')->get();
        $categories = EnglishCategory::where('post_type', $postType)
            ->whereNull('parent_id') // only top-level
            ->with('allChildren')    // load children recursively
            ->orderBy('sort_number')
            ->get();
        return view('admin.english.posts.create', compact('categories', 'postType'));
    }

    // Store new post
    public function store(Request $request)
    {
        //dd($request->all());
        $data = $request->validate([
            'post_type' => 'required|string',
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
            'simple_arabic' => 'nullable|string',

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

        $modelClass = getEnglishModel($data['post_type']);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type selected.'])->withInput();
        }
        $modelClass::create($data);
        return redirect()->back()->with('success', 'Post created successfully.');
        return redirect()->route('admin.english.post.index')->with('success', 'Post created successfully.');
    }

    // Show edit form
    public function edit(Request $request, $id)
    {
        if ($request->has('post_type')) {
            $postType = $request->query('post_type');
        } else {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getEnglishModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $englishPost = $modelClass::findOrFail($id);
        $categories = EnglishCategory::where('post_type', $postType)
            ->whereNull('parent_id') // only top-level parents
            ->with('allChildren')    // eager load children recursively
            ->orderBy('sort_number')
            ->get();

        $selectedIds = old('category_ids', $englishPost->category_ids ?? []);

        return view('admin.english.posts.edit', compact('englishPost', 'categories', 'selectedIds', 'postType'));
    }




    // Update post
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $data = $request->validate([
            'post_type' => 'required|string',
            'title' => 'required|string|max:255',
            'search_text' => 'nullable|string',
            'redirect_deep_link' => 'nullable|string|max:255',
            'roman_data' => 'nullable|string',
            'sort_number' => 'nullable|integer',

            // Arabic
            'arabic_islrc' => 'nullable|boolean',
            'arabic_4line' => 'nullable|boolean',
            'arabic_audio_url' => 'nullable|url|max:255',
            'arabic_content' => 'nullable|string',
            'simple_arabic' => 'nullable|string',

            // Transliteration
            'transliteration_islrc' => 'nullable|boolean',
            'transliteration_4line' => 'nullable|boolean',
            'transliteration_audio_url' => 'nullable|url|max:255',
            'transliteration_content' => 'nullable|string',
            'simple_transliteration' => 'nullable|string',

            // Translation
            'translation_islrc' => 'nullable|boolean',
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

        // Explicitly handle checkboxes - set to false if not present
        $data['arabic_islrc'] = $request->has('arabic_islrc') ? 1 : 0;
        $data['arabic_4line'] = $request->has('arabic_4line') ? 1 : 0;
        $data['transliteration_islrc'] = $request->has('transliteration_islrc') ? 1 : 0;
        $data['transliteration_4line'] = $request->has('transliteration_4line') ? 1 : 0;
        $data['translation_islrc'] = $request->has('translation_islrc') ? 1 : 0;
        $data['translation_4line'] = $request->has('translation_4line') ? 1 : 0;

        $data['category_ids'] = $data['category_ids'] ?? [];

        $postType = $data['post_type'];
        $modelClass = getEnglishModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type selected.'])->withInput();
        }
        $englishPost = $modelClass::find($id);
        $englishPost->update($data);

        return redirect()->back()->with('success', 'Post updated successfully.');
        return redirect()->route('admin.english.post.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->has('post_type')) {
            $postType = $request->query('post_type');
        } else {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getEnglishModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $post = $modelClass::findOrFail($id);
        $post->delete();
        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
