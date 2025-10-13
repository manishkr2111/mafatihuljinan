<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HijriEvent;
use Illuminate\Support\Facades\Cache;

class HijriDateEventController extends Controller
{
    public function index()
    {

        $events = HijriEvent::orderBy('month')->orderBy('date')->get();
        $months = [
            'Muharram',
            'Safar',
            "Rabi'ul Awwal",
            "Rabi'ul Akhir",
            'Jumadal Ula',
            'Jumadal Akhira',
            'Rajab',
            "Sha'ban",
            'Ramadan',
            'Shawwal',
            "Dhul Qa'ada",
            "Dhul Hijja"
        ];
        $languages = ['english', 'hindi', 'french','gujarati']; // Add more as needed
        return view('admin.hijri-date-event.index', compact('events', 'months','languages'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'date' => 'required|integer|min:1|max:30',
            'month' => 'required|string',
            'language' => 'required|string',
            'event' => 'required|string',
            'textcolor' => 'required|string',
        ]);

        HijriEvent::updateOrCreate(
            ['id' => $request->rowid],
            [
                'date' => $request->date,
                'month' => $request->month,
                'event' => $request->event,
                'language' => $request->language,
                'text_color' => $request->textcolor
            ]
        );
        Cache::forget('hijri_events_' . $request->language);
        return redirect()->back()->with('success', 'Event saved successfully!');
    }

    public function edit(HijriEvent $hijriEvent)
    {
        $months = [
            'Muharram',
            'Safar',
            "Rabi'ul Awwal",
            "Rabi'ul Akhir",
            'Jumadal Ula',
            'Jumadal Akhira',
            'Rajab',
            "Sha'ban",
            'Ramadan',
            'Shawwal',
            "Dhul Qa'ada",
            "Dhul Hijja"
        ];
        $languages = ['english', 'hindi', 'french','gujarati']; // Add more as needed
        return view('admin.hijri-date-event.edit', compact('hijriEvent', 'months', 'languages'));
    }

    public function update(Request $request, HijriEvent $hijriEvent)
    {
        $request->validate([
            'date' => 'required|integer|min:1|max:30',
            'month' => 'required|string',
            'event' => 'required|string',
            'language' => 'required|string',
            'textcolor' => 'required|string',
        ]);

        $hijriEvent->update([
            'date' => $request->date,
            'month' => $request->month,
            'event' => $request->event,
            'language' => $request->language,
            'text_color' => $request->textcolor
        ]);
        Cache::forget('hijri_events_' . $request->language);
        return redirect()->route('admin.hijri.date.event')->with('success', 'Event updated successfully!');
    }

    public function destroy(HijriEvent $hijriEvent)
    {
        $hijriEvent->delete();
        return redirect()->route('admin.hijri.date.event')->with('success', 'Event deleted successfully!');
    }
}
