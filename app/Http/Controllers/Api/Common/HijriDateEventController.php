<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HijriEvent;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\HijriDateService;

class HijriDateEventController extends Controller
{
    // Return all events or filter by language
    public function index(Request $request)
    {
        $language = $request->get('language', 'english');
        try {
            $cacheKey = "hijri_events_" . $language;

            $events = Cache::rememberForever($cacheKey, function () use ($language) {
                return HijriEvent::where('language', $language)
                    ->select('id', 'date', 'month', 'event', 'text_color')
                    ->get();
            });

            return response()->json([
                'status' => true,
                'message' => 'Events fetched successfully',
                'language' => $language,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching events'
            ], 500);
        }
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
            'status' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ]);
    }




    ////////////////////////////////////////////////////
    public function getCurrentHijriDate(Request $request)
    {
        //dd('hello');
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
        $datediff = 0;
        $setting =  Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->setting_value;
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
            'status' => true,
            'message' => 'Current Hijri date fetched successfully',
            'data' => [
                'hijri_date' => $hijridate,
                'hijri_day' => $hijriday,
                'hijri_month' => $hijrimonth,
                'hijri_monthname' => $hijrimonthname,
                'hijri_year' => $hijri->get_year()
            ]
        ]);
    }

    public function getHijriDateWithEvents(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        $time = $request->input('time', date("h:i:sa"));
        $ip = $request->input('ip', $request->ip());
        $language = $request->input('language', 'english');
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
        $datediff = 0;
        $setting =  Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->setting_value;
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
        $event = HijriEvent::getEventForDate($hijriday, $hijrimonthname, $language);
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
            'status' => true,
            'message' => 'Hijri date with event fetched successfully',
            'data' => [
                'hijri_date' => $hijridate,
                'event' => $eventname,
                'event_color' => $eventcolor
            ]
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
        $datediff = 0;
        $setting =  Setting::where('setting_key', 'hijri_date_diff')->first();
        if ($setting) {
            $datediff = (int)$setting->setting_value;
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
