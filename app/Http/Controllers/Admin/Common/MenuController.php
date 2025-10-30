<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Menu;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    // Show all menus
    public function index()
    {
        $menus = Menu::orderBy('id', 'desc')->get();
        return view('admin.menus.index', compact('menus'));
    }

    // Show create form
    public function create()
    {
        return view('admin.menus.create');
    }

    // Store new menu
    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'language'  => 'required|string|max:10',
            'sort_number' => 'required|integer',
            'post_type'   => [
                'required',
                'string',
                Rule::in(array_keys(commonPostTypeOptions())),
            ],
        ]);

        try {
            \App\Models\Menu::create([
                'menu_name' => $request->menu_name,
                'language'  => $request->language,
                'sort_number' => $request->sort_number,
                'post_type' => $request->post_type
            ]);

            return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully!');
        } catch (QueryException $e) {
            // Check if it is a unique constraint violation
            if ($e->getCode() === '23000') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['menu_name' => 'This menu name already exists for the selected language.']);
            }
            throw $e; // Rethrow other exceptions
        }
    }

    // Show edit form
    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'language'  => 'required|string|max:10',
            'sort_number' => 'required|integer',
            'post_type'   => [
                'required',
                'string',
                Rule::in(array_keys(commonPostTypeOptions())),
            ],
        ]);
        //dd($request->sort_number);
        try {
            $menu->update([
                'menu_name' => $request->menu_name,
                'language'  => $request->language,
                'sort_number' => (int)$request->sort_number,
                'post_type' => $request->post_type
            ]);

            return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully!');
        } catch (QueryException $e) {
            // Handle unique constraint violation
            if ($e->getCode() === '23000') {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['menu_name' => 'This menu name already exists for the selected language.']);
            }

            throw $e; // Rethrow other exceptions
        }
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect()->back()->with('success', 'Menu deleted successfully!');
    }
}
