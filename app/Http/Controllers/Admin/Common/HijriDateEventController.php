<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HijriEvent;
use App\Models\Setting;
use App\Services\HijriDateService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
        $languages = ['english', 'hindi', 'french', 'gujarati'];
        $datediff = Setting::where('setting_key', 'hijri_date_diff')->value('setting_value') ?? 0;

        $date = date('Y-m-d');
        $datevar = new \DateTime($date);
        $datevar->modify($datediff . ' day');
        $datevar = $datevar->format('Y-m-d');
        $hijri = new HijriDateService(strtotime($datevar));
        $hijrimonth = $hijri->get_month();
        $hijrimonthname = $hijri->get_month_name($hijri->get_month());
        $hijriday = $hijri->get_day();
        $hijriYear = $hijri->get_year();
        $combined_date = $hijriday . ' ' . $hijrimonthname . ' ' . $hijriYear . 'H';
        //dd($combined_date);

        return view(
            'admin.hijri-date-event.index',
            compact(
                'events',
                'months',
                'languages',
                'datediff',
                'hijrimonthname',
                'hijrimonth',
                'hijriday',
                'hijriYear',
                'combined_date'
            )
        );
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
        $languages = ['english', 'hindi', 'french', 'gujarati']; // Add more as needed
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

    /// set hijri date difference
    public function dayDifference(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'day-difference' => 'required|integer|min:-5|max:5',
        ]);
        $datediff = $request->input('day-difference');
        Setting::updateOrCreate(['setting_key' => 'hijri_date_diff'], ['setting_value' => $datediff]);
        return redirect()->back()->with('success', 'Day difference is set to ' . $datediff);
    }
    ////////////////////////////////////////////////////
    public function getCurrentHijriDate(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $time = $request->input('time', date("h:i:sa"));
        $ip = $request->input('ip', $request->ip());
        $dayDifference = $request->day_diff;
        // Get user location based on IP
        $location = $this->getLocationFromIP($ip);

        $lat = $location['lat'] ?? '21.3891'; // Default latitude (example: Mecca)
        $long = $location['lon'] ?? '39.8579'; // Default longitude

        $cyear = date('Y', strtotime($date));
        $monthc = date('m', strtotime($date));

        // Get prayer times from API
        $apiUrl = "http://api.aladhan.com/v1/calendar/" . $cyear . "/" . $monthc . "?latitude=" . $lat . "&longitude=" . $long . "&method=0";

        try {
            $response = Http::get($apiUrl);
            $datedata = $response->json();
        } catch (\Exception $e) {
            $datedata = null;
        }

        $currentday = date("d M Y", strtotime($date));
        $mgtfinaltime = '';

        if (isset($datedata['data']) && is_array($datedata['data'])) {
            foreach ($datedata['data'] as $day) {
                if (isset($day['date']['readable']) && $day['date']['readable'] == $currentday) {
                    $magribtime = $day['timings']['Maghrib'] ?? '';
                    $mgt = explode(" ", $magribtime);
                    $mgtfinaltime = $mgt[0] ?? '';
                    break;
                }
            }
        }

        // Get date difference setting (default to 0)
        $datediff = 0; // In a real Laravel app, you would get this from settings: get_option('hijri_date_diff')
        $setting = Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->value;
        }

        // Check if time is after maghrib
        if ($mgtfinaltime != '') {
            if ($this->greaterDate(date("H:i", strtotime($time)), $mgtfinaltime)) {
                $datediff = $datediff + 1;
            }
        } else {
            if ($this->greaterDate($time, "7:00:00 PM")) {
                $datediff = $datediff + 1;
            }
        }
        if($dayDifference){
            $datediff = $dayDifference;
        }
        $datevar = new \DateTime($date);
        $datevar->modify($datediff . ' day');
        $datevar = $datevar->format('Y-m-d');

        $hijri = new HijriDateService(strtotime($datevar));

        $hijridate = $hijri->get_date(); // 3 Syawal 1408H
        $hijriday = $hijri->get_day(); // 3
        $hijrimonth = $hijri->get_month(); // Syawal
        $hijrimonthname = $hijri->get_month_name($hijrimonth);
        $hijrimonthname = str_replace("'", "", $hijrimonthname);

        return response()->json([
            'hijri_date' => $hijridate,
            'hijri_day' => $hijriday,
            'hijri_month' => $hijrimonth,
            'hijri_monthname' => $hijrimonthname,
            'hijri_year' => $hijri->get_year()
        ]);
    }

    public function getHijriDateWithEvents(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $time = $request->input('time', date("h:i:sa"));
        $ip = $request->input('ip', $request->ip());
        $dayDifference = $request->day_diff;
        // Get user location based on IP
        $location = $this->getLocationFromIP($ip);

        $lat = $location['lat'] ?? '21.3891'; // Default latitude (example: Mecca)
        $long = $location['lon'] ?? '39.8579'; // Default longitude

        $cyear = date('Y', strtotime($date));
        $monthc = date('m', strtotime($date));

        // Get prayer times from API
        $apiUrl = "http://api.aladhan.com/v1/calendar/" . $cyear . "/" . $monthc . "?latitude=" . $lat . "&longitude=" . $long . "&method=0";

        try {
            $response = Http::get($apiUrl);
            $datedata = $response->json();
        } catch (\Exception $e) {
            $datedata = null;
        }

        $currentday = date("d M Y", strtotime($date));
        $mgtfinaltime = '';

        if (isset($datedata['data']) && is_array($datedata['data'])) {
            foreach ($datedata['data'] as $day) {
                if (isset($day['date']['readable']) && $day['date']['readable'] == $currentday) {
                    $magribtime = $day['timings']['Maghrib'] ?? '';
                    $mgt = explode(" ", $magribtime);
                    $mgtfinaltime = $mgt[0] ?? '';
                    break;
                }
            }
        }

        // Get date difference setting (default to 0)
        $datediff = 0; // In a real Laravel app, you would get this from settings
        $setting = Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->value;
        }
        // Check if time is after maghrib
        if ($mgtfinaltime != '') {
            if ($this->greaterDate(date("H:i", strtotime($time)), $mgtfinaltime)) {
                $datediff = $datediff + 1;
            }
        } else {
            if ($this->greaterDate($time, "7:00:00 PM")) {
                $datediff = $datediff + 1;
            }
        }
        if($dayDifference){
            $datediff = $dayDifference;
        }
        $datevar = new \DateTime($date);
        $datevar->modify($datediff . ' day');
        $datevar = $datevar->format('Y-m-d');

        $hijri = new HijriDateService(strtotime($datevar));

        $hijridate = $hijri->get_date(); // 3 Syawal 1408H
        $hijriday = $hijri->get_day(); // 3
        $hijrimonth = $hijri->get_month(); // Syawal
        $hijrimonthname = $hijri->get_month_name($hijrimonth);
        //$hijrimonthname = str_replace("'", "", $hijrimonthname);

        // Fetch events from the database
        $event = HijriEvent::getEventForDate($hijriday, $hijrimonthname);
        //dd($event,$hijriday,$hijrimonthname);
        $eventname = '';
        $eventcolor = '';

        if ($event) {
            if ($event->textcolor == 'Black') {
                $eventcolor = "#000";
            } elseif ($event->textcolor == 'White') {
                $eventcolor = "#FFFFFF";
            } elseif ($event->textcolor == 'LightBlack') {
                $eventcolor = "#4D4646";
            } else {
                $eventcolor = "#1b8415";
            }
            $eventname = $event->event;
        }

        return response()->json([
            'hijri_date' => $hijridate,
            'event' => $eventname,
            'event_color' => $eventcolor
        ]);
    }

    public function getRamadanDate(Request $request)
    {
        $dateParam = $request->input('date', date('Y-m-d'));
        $dayDifference = $request->day_diff;
        $timezone = date_default_timezone_get();
        $date = new \DateTime("now", new \DateTimeZone($timezone));
        $currenttime = $date->format('h:i:s A');

        $date = new \DateTime($dateParam);
        $cyear = date('Y');
        $monthc = date('m');

        $ip = $request->input('ip', $request->ip());

        // Get user location based on IP
        $location = $this->getLocationFromIP($ip);

        $lat = $location['lat'] ?? '21.3891'; // Default latitude (example: Mecca)
        $long = $location['lon'] ?? '39.8579'; // Default longitude

        $cyear = date('Y');
        $monthc = date('m');

        // Get prayer times from API
        $apiUrl = "http://api.aladhan.com/v1/calendar/" . $cyear . "/" . $monthc . "?latitude=" . $lat . "&longitude=" . $long . "&method=0";

        try {
            $response = Http::get($apiUrl);
            $datedata = $response->json();
        } catch (\Exception $e) {
            $datedata = null;
        }

        $currentday = date('d M Y');
        $mgtfinaltime = '';

        if (isset($datedata['data']) && is_array($datedata['data'])) {
            foreach ($datedata['data'] as $day) {
                if (isset($day['date']['readable']) && $day['date']['readable'] == $currentday) {
                    $magribtime = $day['timings']['Maghrib'] ?? '';
                    $mgt = explode(" ", $magribtime);
                    $mgtfinaltime = $mgt[0] ?? '';
                    break;
                }
            }
        }

        // Get date difference setting (default to 0)
        $datediff = 0; // In a real Laravel app, you would get this from settings
        $setting = Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->value;
        }
        // Check if time is after maghrib
        if ($mgtfinaltime != '') {
            if ($this->greaterDate(date("H:i"), $mgtfinaltime)) {
                $datediff = $datediff + 1;
            }
        } else {
            if ($this->greaterDate(date("h:i:sa"), "7:00:00 PM")) {
                $datediff = $datediff + 1;
            }
        }
        if($dayDifference){
            $datediff = $dayDifference;
        }
        $date->modify($datediff . ' day');
        $date = $date->format('Y-m-d');

        $hijri = new HijriDateService(strtotime($date));
        $hijriday = $hijri->get_day();
        $hijrimonthname = $hijri->get_month_name($hijri->get_month());
        $hijrimonthname = str_replace("'", "", $hijrimonthname);

        return response()->json([
            'hijri_day' => $hijriday,
            'hijri_monthname' => $hijrimonthname
        ]);
    }

    private function getLocationFromIP($ip)
    {
        try {
            $response = Http::get("http://ip-api.com/json/$ip");
            $location = $response->json();

            if (isset($location['status']) && $location['status'] === 'success') {
                return [
                    'lat' => $location['lat'],
                    'lon' => $location['lon']
                ];
            }
        } catch (\Exception $e) {
            // Return default values if API call fails
        }

        return [
            'lat' => '21.3891',
            'lon' => '39.8579'
        ];
    }

    private function greaterDate($start_date, $end_date)
    {
        $start = strtotime($start_date);
        $end = strtotime($end_date);

        return ($start - $end) > 0 ? 1 : 0;
    }
}
