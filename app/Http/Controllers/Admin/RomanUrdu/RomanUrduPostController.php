<?php

namespace App\Http\Controllers\Admin\RomanUrdu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RomanUrdu\Category;
use Illuminate\Support\Str;

class RomanUrduPostController extends Controller
{

    // Show all posts
    public function index_(Request $request)
    {
        //dd($request->all());
        $postType = $request->post_type;
        if (!$postType) {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $posts = $modelClass::orderBy('sort_number', 'asc')->paginate(50);
        $allCategories = Category::all();
        return view('admin.RomanUrdu.posts.index', compact('posts', 'postType', 'allCategories'));
    }

    public function index(Request $request)
    {
        $postType = $request->post_type;
        if (!$postType) {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }

        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $query = $modelClass::query();
        // ✅ Search by title or category name
        if ($request->filled('search')) {
            $search = trim($request->search);
            // Find matching category IDs based on name
            $matchedCategoryIds = Category::where('name', 'like', "%{$search}%")
                ->where('post_type', $postType)
                ->pluck('id')
                ->map(fn($id) => (string) $id) // convert to string for JSON match
                ->toArray();

            $query->where(function ($q) use ($search, $matchedCategoryIds) {
                $q->where('title', 'like', "%{$search}%");

                if (!empty($matchedCategoryIds)) {
                    foreach ($matchedCategoryIds as $id) {
                        $q->orWhereJsonContains('category_ids', $id);
                    }
                }
            });
        }
        // ✅ Filter by category dropdown
        if ($request->filled('category')) {
            $categoryName = strtolower($request->category);
            // Find the matching category ID
            $matchedCategoryIds = Category::whereRaw('LOWER(name) = ?', [$categoryName])
                ->where('post_type', $postType)
                ->pluck('id')
                ->map(fn($id) => (string) $id) // important: make it string
                ->toArray();

            if (!empty($matchedCategoryIds)) {
                $query->where(function ($q) use ($matchedCategoryIds) {
                    foreach ($matchedCategoryIds as $id) {
                        $q->orWhereJsonContains('category_ids', $id);
                    }
                });
            }
        }
        $sortOrder = $request->get('sort_order', 'asc'); // default asc
        $query->orderBy('sort_number', $sortOrder);
        $posts = $query->orderBy('sort_number', 'asc')->paginate(50);
        $allCategories = Category::where('post_type', $postType)->get();
        return view('admin.RomanUrdu.posts.index', compact('posts', 'postType', 'allCategories'));
    }

    // Show create form
    public function create(Request $request)
    {
        $postType = $request->query('post_type');
        if (!$postType) {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $categories = Category::where('post_type', $postType)
            ->whereNull('parent_id') // only top-level
            ->with('allChildren')    // load children recursively
            ->orderBy('sort_number')
            ->get();
        return view('admin.RomanUrdu.posts.create', compact('categories', 'postType'));
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
            'category_ids.*' => 'integer|exists:roman_urdu_categories,id',

            'status' => 'required|in:draft,published,archived',
        ]);

        // Save categories as JSON
        /*if (!empty($data['category_ids'])) {
            $data['category_ids'] = json_encode($data['category_ids']);
        }*/
        $data['category_ids'] = $data['category_ids'] ?? [];
        $data['word_meanings'] = json_encode($request->word_meanings);
        $modelClass = getRomanUrduModel($data['post_type']);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type selected.'])->withInput();
        }


        foreach (['internal_link', 'next_post_url'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = Str::slug($data[$field]);
            }
        }
        //  If internal_link exists, copy content from that post
        if (!empty($data['internal_link'])) {
            $linkedPost = $modelClass::where('slug', $data['internal_link'])->first();
            if ($linkedPost) {
                // Copy specific fields you want from linked post
                $copyFields = [
                    'arabic_content',
                    'arabic_audio_url',
                    'simple_arabic',
                    'arabic_4line',
                    'arabic_islrc',
                    'transliteration_content',
                    'transliteration_audio_url',
                    'simple_transliteration',
                    'transliteration_4line',
                    'transliteration_islrc',
                    'translation_content',
                    'translation_audio_url',
                    'simple_translation',
                    'translation_4line',
                    'translation_islrc',
                    'category_ids',
                    'word_meanings'
                ];

                foreach ($copyFields as $field) {
                    $data[$field] = $linkedPost->$field;
                }
            }
        }

        $post = $modelClass::create($data);
        $slug = Str::slug($data['title']) . '-' . $post->id;
        $post->update([
            'slug' => $slug,
            'sort_number' => $post->id,
        ]);

        // updated menu time to retreve data in refresh content
        lastDataUpdatedTime($data['post_type'], 'roman urdu');

        return redirect()->back()->with('success', 'Post created successfully.');
        return redirect()->route('admin.roman-urdu.post.index')->with('success', 'Post created successfully.');
    }

    // Show edit form
    public function edit(Request $request, $id)
    {
        if ($request->has('post_type')) {
            $postType = $request->query('post_type');
        } else {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $Post = $modelClass::findOrFail($id);
        $categories = Category::where('post_type', $postType)
            ->whereNull('parent_id') // only top-level parents
            ->with('allChildren')    // eager load children recursively
            ->orderBy('sort_number')
            ->get();

        $selectedIds = old('category_ids', $Post->category_ids ?? []);
        $wordMeanings = json_decode($Post->word_meanings, true) ?? [];
        return view('admin.RomanUrdu.posts.edit', compact('Post', 'categories', 'selectedIds', 'postType', 'wordMeanings'));
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
            'category_ids.*' => 'integer|exists:roman_urdu_categories,id',

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
        $data['word_meanings'] = json_encode($request->word_meanings);
        $postType = $data['post_type'];
        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type selected.'])->withInput();
        }
        $Post = $modelClass::find($id);

        foreach (['internal_link', 'next_post_url'] as $field) {
            if (!empty($data[$field])) {
                $data[$field] = Str::slug($data[$field]);
            }
        }
        //  If internal_link exists, copy content from that post
        if (!empty($data['internal_link'])) {
            $linkedPost = $modelClass::where('slug', $data['internal_link'])->first();
            if ($linkedPost) {
                // Copy specific fields you want from linked post
                $copyFields = [
                    'arabic_content',
                    'arabic_audio_url',
                    'simple_arabic',
                    'arabic_4line',
                    'arabic_islrc',
                    'transliteration_content',
                    'transliteration_audio_url',
                    'simple_transliteration',
                    'transliteration_4line',
                    'transliteration_islrc',
                    'translation_content',
                    'translation_audio_url',
                    'simple_translation',
                    'translation_4line',
                    'translation_islrc',
                    'category_ids',
                    'word_meanings'
                ];

                foreach ($copyFields as $field) {
                    $data[$field] = $linkedPost->$field;
                }
            }
        }

        $Post->update($data);

        // updated menu time to retreve data in refresh content
        lastDataUpdatedTime($data['post_type'], 'roman urdu');

        return redirect()->back()->with('success', 'Post updated successfully.');
        return redirect()->route('admin.roman-urdu.post.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->has('post_type')) {
            $postType = $request->query('post_type');
        } else {
            return redirect()->back()->withErrors(['post_type' => 'Post type is required.']);
        }
        $modelClass = getRomanUrduModel($postType);
        if (!$modelClass) {
            return redirect()->back()->withErrors(['post_type' => 'Invalid post type specified.']);
        }
        $post = $modelClass::findOrFail($id);
        $post->delete();

        // updated menu time to retreve data in refresh content
        lastDataUpdatedTime($postType, 'roman urdu');

        return redirect()->back()->with('success', 'Post deleted successfully.');
    }
}
