<?php

namespace App\Http\Controllers\Admin\English;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnglishCategory;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Generate an array of categories for dropdown with indentation for nested children.
     */
    private function getCategoryOptions($categories, $level = 0, &$result = [])
    {
        foreach ($categories as $category) {
            $result[$category->id] = str_repeat('-', $level + 0) . ' ' . $category->name;
            if ($category->children && $category->children->count()) {
                $this->getCategoryOptions($category->children, $level + 1, $result);
            }
        }
        return $result;
    }

    /**
     * Display a listing of categories with nested children.
     */
    public function index(Request $request)
    {
        $postType = $request->input('post_type', 'sahifas-shlulbayt');

        $query = EnglishCategory::whereNull('parent_id')->with('allChildren')->orderBy('sort_number');
        //dd($query);
        if (!empty($postType)) {
            $query->where('post_type', $postType);
        }

        $categories = $query->get();
        //dd( $categories );

        return view('admin.english.category.index', compact('categories', 'postType'));
    }



    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = EnglishCategory::whereNull('parent_id')->with('children')->get();
        $categoryOptions = $this->getCategoryOptions($categories);

        return view('admin.english.category.create', compact('categoryOptions'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $post_type = $request->input('post_type', $request->post_type);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:english_categories,slug',
            'description' => 'nullable|string',
            'sort_number' => 'nullable|integer',
            'parent_id' => 'nullable|exists:english_categories,id',
        ]);

        EnglishCategory::create($request->all());
        Cache::forget('english_categories_' . $post_type);

        return redirect()->route('admin.english.category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit($id)
    {
        $category = EnglishCategory::findOrFail($id);
        $categories = EnglishCategory::whereNull('parent_id')->with('children')->get();
        $categoryOptions = $this->getCategoryOptions($categories);

        return view('admin.english.category.edit', compact('category', 'categoryOptions'));
    }

    /**
     * Update the category.
     */
    public function update(Request $request, $id)
    {
        $category = EnglishCategory::findOrFail($id);
        $post_type = $request->input('post_type', $request->post_type);
        Cache::forget('english_categories_' . $post_type);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:english_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'sort_number' => 'nullable|integer',
            'parent_id' => 'nullable|exists:english_categories,id',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.english.category.index')->with('success', 'Category updated successfully.');
    }
    public function getParentCategories(Request $request)
    {
        $postType = $request->get('post_type');
        $categories = EnglishCategory::where('post_type', $postType)
            // ->whereNull('parent_id')
            ->pluck('name', 'id');

        return response()->json($categories);
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        $category = EnglishCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.english.category.index')->with('success', 'Category deleted successfully.');
    }
}
