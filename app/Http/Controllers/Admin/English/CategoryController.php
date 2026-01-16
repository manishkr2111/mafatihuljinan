<?php

namespace App\Http\Controllers\Admin\English;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\English\EnglishCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $postType = $request->input('post_type');
        $query = EnglishCategory::whereNull('parent_id')
            ->with('allChildren')
            ->orderBy('sort_number');
        if ($postType) {
            $query->where('post_type', $postType);
        }
        $categories = $query->get();
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
        // dd($request->all());
        $post_type = $request->input('post_type', $request->post_type);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:english_categories,slug',
            'description' => 'nullable|string',
            'sort_number' => 'nullable|integer',
            'parent_id' => 'nullable|exists:english_categories,id',
            'popup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return redirect()->back()->with('error', 'Invalid Post Type');
        }

        if ($request->parent_id) {
            $parent = EnglishCategory::find($request->parent_id);

            $level = 1;
            while ($parent && $parent->parent_id) {
                $level++;
                $parent = $parent->parent;
            }

            if ($level >= 6) {
                return redirect()->back()->with('error', 'You cannot create a category deeper than 5 levels.');
            }
        }
        if ($validated['sort_number'] == '0' || !$validated['sort_number']) {
            $validated['sort_number'] =
                EnglishCategory::where('post_type', $post_type)
                ->max('sort_number') + 1;
        }
        $validated['slug'] = Str::slug($validated['slug'], '-');

        // Upload popup image
        if ($request->hasFile('popup_image')) {
            $file = $request->file('popup_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'amaal-namaz/english-popup-image',
                $fileName,
                'public'
            );
            $validated['popup_image'] = $path; // save path in DB
        }

        $Category = EnglishCategory::create($validated);
        $Category->post_type = $post_type;
        $Category->save();
        Cache::forget('english_categories_' . $post_type);
        Cache::forget('english_all_categories');
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
        Cache::forget('english_all_categories');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:english_categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'sort_number' => 'nullable|integer',
            'parent_id' => 'nullable|exists:english_categories,id',
            'popup_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // dd($validated);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return redirect()->back()->with('error', 'Invalid Post Type');
        }
        $validated['slug'] = Str::slug($validated['slug'], '-');
        // Upload popup image
        if ($request->hasFile('popup_image')) {
            $oldFile = $category->popup_image;
            if ($oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
            $file = $request->file('popup_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(
                'amaal-namaz/english-popup-image',
                $fileName,
                'public'
            );
            $validated['popup_image'] = $path; // save path in DB
        }else{
            $validated['popup_image'] = $category->popup_image;
        }
        $category->update($validated);
        $category->post_type = $post_type;
        $category->save();

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



    // Deeplink Categories
    public function createDeeplink()
    {
        $categories = EnglishCategory::whereNull('parent_id')->with('children')->get();
        $categoryOptions = $this->getCategoryOptions($categories);

        return view('admin.english.category.deeplink.create', compact('categoryOptions'));
    }

    public function deeplinkStore(Request $request)
    {
        $post_type = $request->input('post_type', $request->post_type);
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'deeplink_url' => 'required|string',
            'sort_number' => 'nullable|integer',
            'parent_id' => 'nullable|exists:english_categories,id',
            // 'slug' => 'required|unique:english_categories,slug',
        ]);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return redirect()->back()->with('error', 'Invalid Post Type');
        }

        $deeplinkUrlCategory = EnglishCategory::where('slug', $validated['deeplink_url'])->first();
        if (!$deeplinkUrlCategory || ($deeplinkUrlCategory && !empty($deeplinkUrlCategory->deeplink_url))) {
            return redirect()->back()->with('error', 'Invalid Deeplink URL');
        }
        if($deeplinkUrlCategory && $deeplinkUrlCategory->post_type == $post_type){
            return redirect()->back()->with('error', 'Invalid Deeplink URL (can not use same deeplink url for same post type)');
        }
        if ($request->parent_id) {
            $parent = EnglishCategory::find($request->parent_id);

            $level = 1;
            while ($parent && $parent->parent_id) {
                $level++;
                $parent = $parent->parent;
            }

            if ($level >= 6) {
                return redirect()->back()->with('error', 'You cannot create a category deeper than 5 levels.');
            }
        }

        if ($validated['sort_number'] == '0' || !$validated['sort_number']) {
            $validated['sort_number'] =
                EnglishCategory::where('post_type', $post_type)
                ->max('sort_number') + 1;
        }
        // generate slug
        $baseSlug = $validated['name']
            ? Str::slug($validated['name'])
            : Str::slug(parse_url($validated['deeplink_url'], PHP_URL_PATH) ?? 'deeplink');

        $slug = $baseSlug;
        $count = 1;
        while (EnglishCategory::where('slug', $slug)->exists()) {
            $slug = 'deeplink-' . $baseSlug . '-' . $count++;
        }
        $validated['slug'] = $slug;
        //generate slug end 
        if (!$validated['name']) {
            $validated['name'] = $deeplinkUrlCategory->name;
        }
        $Category = EnglishCategory::create($validated);
        $Category->post_type = $post_type;
        $Category->save();
        Cache::forget('english_categories_' . $post_type);
        Cache::forget('english_all_categories');
        return redirect()->route('admin.english.category.index')->with('success', 'Category created successfully.');
    }


    public function deeplinkEdit($id)
    {
        $category = EnglishCategory::findOrFail($id);

        // Parent categories except self
        $categoryOptions = EnglishCategory::where('post_type', $category->post_type)
            ->where('id', '!=', $category->id)
            ->pluck('name', 'id');

        return view('admin.english.category.deeplink.edit', compact(
            'category',
            'categoryOptions'
        ));
    }

    public function deeplinkUpdate(Request $request, $id)
    {
        $category = EnglishCategory::findOrFail($id);
        $post_type = $request->post_type;

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'sort_number' => 'nullable|integer',
            'deeplink_url' => 'required|string',
            'parent_id' => 'nullable|exists:english_categories,id',
        ]);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return redirect()->back()->with('error', 'Invalid Post Type');
        }
        if ($category->deeplink_url != $validated['deeplink_url']) {

            $deeplinkUrlCategory = EnglishCategory::where('slug', $validated['deeplink_url'])
                ->whereNot('id', $category->id)
                ->first();
            if (!$deeplinkUrlCategory || ($deeplinkUrlCategory && !empty($deeplinkUrlCategory->deeplink_url))) {
                return redirect()->back()->with('error', 'Invalid Deeplink URL');
            }
        }

        // Parent depth check (same as create)
        if ($request->parent_id) {
            $parent = EnglishCategory::find($request->parent_id);
            $level = 1;
            while ($parent && $parent->parent_id) {
                $level++;
                $parent = $parent->parent;
            }
            if ($level >= 6) {
                return back()->with('error', 'You cannot create a category deeper than 5 levels.');
            }
        }

        // Name fallback
        if (!$validated['name']) {
            $validated['name'] = $deeplinkUrlCategory->name;
        }

        // Sort number auto-fix
        if (!$validated['sort_number'] || $validated['sort_number'] == 0) {
            $validated['sort_number'] =
                EnglishCategory::where('post_type', $post_type)->max('sort_number') + 1;
        }

        $category->update($validated);
        $category->post_type = $post_type;
        $category->save();

        Cache::forget('english_categories_' . $post_type);
        Cache::forget('english_all_categories');

        return redirect()
            ->route('admin.english.category.index')
            ->with('success', 'Deeplink category updated successfully.');
    }
}
