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

        //  User count by language
        $userCount = User::selectRaw('language, COUNT(*) as total')
            ->groupBy('language')
            ->pluck('total', 'language')
            ->toArray();

        //  Post types list
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

    public function showLrcEnabledPosts($language, $postType, $lrcType)
    {
        // Determine model based on language
        if ($language === 'english') {
            $modelClass = getEnglishModel($postType);
        } elseif ($language === 'gujarati') {
            $modelClass = getGujaratiModel($postType);
        } elseif ($language === 'hindi') {
            $modelClass = getHindiModel($postType);
        } elseif ($language === 'urdu') {
            $modelClass = getUrduModel($postType);
        } elseif ($language === 'roman urdu') {
            $modelClass = getRomanUrduModel($postType);
        } elseif ($language === 'french') {
            $modelClass = getFrenchModel($postType);
        } elseif ($language === 'swahili') {
            $modelClass = getSwahiliModel($postType);
        } else {
            abort(404, 'Unsupported language');
        }

        if (!$modelClass || !class_exists($modelClass)) {
            abort(404, 'Invalid post type');
        }

        // Map the LRC type to the correct column name
        $lrcColumnMap = [
            'arabic' => 'arabic_islrc',
            'transliteration' => 'transliteration_islrc',
            'translation' => 'translation_islrc',
        ];

        if (!array_key_exists($lrcType, $lrcColumnMap)) {
            abort(404, 'Invalid LRC type');
        }

        $column = $lrcColumnMap[$lrcType];

        // Fetch posts with LRC enabled
        $posts = $modelClass::where($column, 1)->paginate(20);

        return view('admin.lrc-posts', compact('posts', 'postType', 'language', 'lrcType'));
    }


    public function generateApiToken()
    {
        $token = Str::random(60);
        Setting::set('api_access_token', $token);
        return redirect()->route('admin.dashboard')
            ->with('success', 'API token regenerated successfully.');
    }


    public function uploadAudiopage(Request $request)
    {
        $language = $request->get('language', 'english');
        $directory = env('AUDIO_DIRECTORY').'/'.$language;
        $webUrl = env('AUDIO_WEBURL').'/'.$language.'/';
        // dd($directory,$webUrl);
        $files = [];

        if (is_dir($directory)) {
            foreach (scandir($directory) as $file) {
                if ($file !== '.' && $file !== '..') {
                    if (preg_match('/\.(mp3|wav|aac)$/i', $file)) {
                        $files[] = [
                            'name' => $file,
                            'url'  => $webUrl . $file
                        ];
                    }
                }
            }
        }

        // Sort newest first
        $files = array_reverse($files);

        // Pagination settings
        // Get per_page from request, default to 50
        $perPage = $request->get('per_page', 25);

        // Validate per_page to prevent abuse
        $allowedPerPage = [25, 50, 100, 150, 200];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 25; // fallback to default
        }
        $page = request()->get('page', 1); // current page
        $offset = ($page - 1) * $perPage;

        // Slice list for current page
        $paginatedItems = array_slice($files, $offset, $perPage);

        // Create Laravel Paginator manually
        $filesPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedItems,
            count($files),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return view('admin.audio.upload-audio', compact('filesPaginated', 'language'));
    }



    public function uploadAudio(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,aac',
            'post_type' => 'required|string',
            'language' => 'required|string'
        ]);

        try {
            $audio = $request->file('audio');
            $postType = strtolower($request->post_type);

            $originalName = pathinfo($audio->getClientOriginalName(), PATHINFO_FILENAME);
            $originalName = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalName);

            $randomId = uniqid();
            $extension = $audio->getClientOriginalExtension();

            $fileName = $postType . '_' . $originalName . '_' . $randomId . '.' . $extension;

            $destination = env('AUDIO_DIRECTORY') . '/' . $request->language;
            $audio->move($destination, $fileName);

            $url = env('AUDIO_WEBURL') .'/'.$request->language . '/'. $fileName;

            return back()->with([
                'success' => 'Audio uploaded successfully!',
                'audio_url' => $url
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function deleteAudio(Request $request)
    {
        $fileName = $request->file_name;

        if (!$fileName) {
            return back()->with('error', 'File name is missing.');
        }

        $directory = env('AUDIO_DIRECTORY');
        $filePath = $directory . '/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
            return back()->with('success', 'Audio file deleted successfully.');
        } else {
            return back()->with('error', 'File not found.');
        }
    }
}
