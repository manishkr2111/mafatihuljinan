<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\TafsirData;
use Illuminate\Http\Request;

class TafsirDataController extends Controller
{
    public function index()
    {
        $tafsirs = TafsirData::orderBy('id', 'desc')->get();
        return view('admin.tafsir.index', compact('tafsirs'));
    }

    public function create()
    {
        return view('admin.tafsir.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'post_type' => 'required|string',
            'language' => 'required|string',
            'content' => 'nullable|string',
            'tafsir_html_content' => 'nullable|string',
        ]);

        TafsirData::create($validated);

        return redirect()->route('admin.tafsir.index')->with('success', 'Tafsir created successfully.');
    }

    public function edit($id)
    {
        $tafsir = TafsirData::findOrFail($id);
        return view('admin.tafsir.edit', compact('tafsir'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'post_type' => 'required|string',
            'language' => 'required|string',
            'content' => 'nullable|string',
            'tafsir_html_content' => 'nullable|string',
        ]);

        $tafsir = TafsirData::findOrFail($id);
        $tafsir->update($validated);

        return redirect()->route('admin.tafsir.index')->with('success', 'Tafsir updated successfully.');
    }

    public function destroy($id)
    {
        $tafsir = TafsirData::findOrFail($id);
        $tafsir->delete();

        return redirect()->route('admin.tafsir.index')->with('success', 'Tafsir deleted successfully.');
    }
}
