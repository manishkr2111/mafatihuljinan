<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $apiToken = Setting::get('api_access_token');
        $totalUsers = User::count();

        // 🧮 User count by language
        $userCount = User::selectRaw('language, COUNT(*) as total')
            ->groupBy('language')
            ->pluck('total', 'language')
            ->toArray();

        // 🧩 Post types list
        $postTypes = [
            'sahifas-ahlulbayt',
            'surah',
            'dua',
            'daily-dua',
            'amaal-namaz',
            'burial-acts-prayers',
            'amaal',
            'munajat',
            'salaat-namaz',
            'salwaat',
            'tasbih',
            'travel-ziyarat',
            'ziyarat',
            'essential-supplications'
        ];

        // Languages to include
        $languages = ['english', 'gujarati', 'hindi', 'urdu', 'roman urdu', 'french', 'swahili'];

        $postCounts = [];

        foreach ($languages as $lang) {
            foreach ($postTypes as $type) {
                if ($lang === 'english') {
                    $modelClass = getEnglishModel($type);
                } elseif ($lang === 'gujarati') {
                    $modelClass = getGujaratiModel($type);
                } else {
                    $modelClass = null;
                }

                // If model exists, count both total and enabled
                if ($modelClass && class_exists($modelClass)) {
                    $total = $modelClass::count();
                    $arabic_lrc_enabled_count = $modelClass::where('arabic_islrc', 1)->count();
                    $transliteration_lrc_enabled_count = $modelClass::where('transliteration_islrc', 1)->count();
                    $translation_lrc_enabled_count = $modelClass::where('translation_islrc', 1)->count();
                    $postCounts[$lang][$type] = [
                        'total'   => $total,
                        'arabic_lrc_enabled_count' => $arabic_lrc_enabled_count,
                        'transliteration_lrc_enabled_count' => $transliteration_lrc_enabled_count,
                        'translation_lrc_enabled_count' => $translation_lrc_enabled_count,
                    ];
                } else {
                    $postCounts[$lang][$type] = [
                        'total'   => 0,
                        'enabled' => 0,
                    ];
                }
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'userCount',
            'postCounts',
            'apiToken'
        ));
    }

    public function generateApiToken()
    {
        $token = Str::random(60);
        Setting::set('api_access_token', $token);
        return redirect()->route('admin.dashboard')
            ->with('success', 'API token regenerated successfully.');
    }
}
