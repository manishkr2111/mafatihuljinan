<?php

namespace App\Http\Controllers\Admin\Common;
use App\Http\Controllers\Controller; // Important!
use App\Models\MarqueeText;
use Illuminate\Http\Request;

class MarqueeTextController extends Controller
{
    public function index()
    {
        $marquees = MarqueeText::orderBy('language', 'desc')->get();
        return view('admin.marquee.index', compact('marquees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'language' => 'required|string|max:50',
        ]);

        MarqueeText::create([
            'text' => $request->text,
            'language' => $request->language,
        ]);

        return redirect()->back()->with('success', 'Marquee text added successfully!');
    }

    public function edit(MarqueeText $marqueeText)
    {
        return view('admin.marquee.edit', compact('marqueeText'));
    }

    public function update(Request $request, MarqueeText $marqueeText)
    {
        $request->validate([
            'text' => 'required|string',
            'language' => 'required|string|max:50',
        ]);

        $marqueeText->update([
            'text' => $request->text,
            'language' => $request->language,
        ]);

        return redirect()->route('admin.marquee.index')->with('success', 'Marquee text updated successfully!');
    }

    public function destroy(MarqueeText $marqueeText)
    {
        $marqueeText->delete();
        return redirect()->route('admin.marquee.index')->with('success', 'Marquee text deleted successfully!');
    }
}
