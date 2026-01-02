<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\EventPopup;
use Illuminate\Support\Facades\Storage;

class EventPopupController extends Controller
{
    public function index()
    {
        $eventPopups = EventPopup::all();
        return view('admin.event-popup.index', compact('eventPopups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'date' => 'required|integer|min:1|max:31',
            'month' => 'required|integer|min:1|max:12',
            'language' => [
                'required',
                'string',
                'max:50',
                'in:' . implode(',', validLanguages()),
            ],
        ]);

        $existingPopup = EventPopup::where('date', $request->input('date'))
            ->where('month', $request->input('month'))
            ->where('language', $request->input('language'))
            ->first();
        if($existingPopup) {
            return redirect()->back()->with('error', 'Event Popup already exists for this date and month.');
        }
        $eventPopup = new EventPopup();
        $eventPopup->title = $request->input('title');
        $eventPopup->date = $request->input('date');
        $eventPopup->month = $request->input('month');
        $eventPopup->language = $request->input('language');

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $eventPopup->imgurl = Storage::disk('public')->putFileAs('eventpopup', $file, $filename);
        }

        $eventPopup->save();

        return redirect()->route('admin.eventpopup')->with('success', 'Event Popup uploaded successfully.');
    }

    public function destroy(EventPopup $eventPopup)
    {
        // delete file also if stored
        if ($eventPopup->imgurl) {
            $relativePath = str_replace(asset('storage/') . '/', '', $eventPopup->imgurl);
            Storage::disk('public')->delete($relativePath);
        }

        $eventPopup->delete();

        return redirect()
            ->route('admin.eventpopup')
            ->with('success', 'Event Popup deleted successfully.');
    }
}
