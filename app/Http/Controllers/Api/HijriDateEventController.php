<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HijriEvent;
use Illuminate\Support\Facades\Cache;

class HijriDateEventController extends Controller
{
    // Return all events or filter by language
    public function index(Request $request)
    {
        $language = $request->get('language', 'english');
        $cacheKey = "hijri_events_" . $language;

        $events = Cache::rememberForever($cacheKey, function () use ($language) {
            return HijriEvent::where('language', $language)->get();
        });

        return response()->json([
            'success' => true,
            'language' => $language,
            'events' => $events
        ]);
    }

    // Store new event
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'hijri_date' => 'required|string',
            'gregorian_date' => 'nullable|string',
            'language' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $event = HijriEvent::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ]);
    }
}
