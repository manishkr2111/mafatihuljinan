<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Services\HijriDateService;
use Illuminate\Support\Facades\Storage;
use App\Models\English\EnglishCategory;
use App\Models\Gujarati\Category as GujaratiCategory;
use App\Models\Hindi\Category as HindiCategory;
use App\Models\French\Category as FrenchCategory;
use App\Models\RomanUrdu\Category as RomanUrduCategory;
use App\Models\Urdu\Category as UrduCategory;
use App\Models\Swahili\Category as SwahiliCategory;

class MenuController extends Controller
{
    public function getContentOld(Request $request)
    {
        $language = $request->get('language', 'english'); // default to English
        try {
            $menus = Menu::where('language', $language)->orderBy('sort_number')
                ->select('id', 'menu_name', 'post_type', 'last_data_updated_at')
                ->get();
            return response()->json([
                'status' => true,
                'language' => $language,
                'message' => 'Menu fetched successfully',
                'data' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching menu: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getCategoryModel(string $language)
    {
        return match ($language) {
            'english'   => EnglishCategory::class,
            'hindi'     => HindiCategory::class,
            'french'    => FrenchCategory::class,
            'gujarati'  => GujaratiCategory::class,
            'romanurdu' => RomanUrduCategory::class,
            'urdu'      => UrduCategory::class,
            'swahili'   => SwahiliCategory::class,
            default     => EnglishCategory::class,
        };
    }

    public function getContent(Request $request)
    {
        $language = $request->get('language', 'english');

        $date = $request->input('date');
        $dayDiff = (int) $request->input('day_diff', 0);

        $dateObj = new \DateTime($date);
        if ($dayDiff !== 0) {
            $dateObj->modify($dayDiff . ' day');
        }

        $hijri = new HijriDateService($dateObj->getTimestamp());

        $day = $hijri->get_day();
        $month = $hijri->get_month_name($hijri->get_month());

        $categoryName = "{$day} {$month}";

        $CategoryModel = $this->getCategoryModel($language);

        $amaalCategory = $CategoryModel::where('name', $categoryName)->first();

        $menus = Menu::where('language', $language)
            ->when(!$amaalCategory, fn($q) => $q->where('post_type', '!=', 'amaal-namaz'))
            ->orderBy('sort_number')
            ->select('id', 'menu_name', 'post_type', 'last_data_updated_at')
            ->get();

        if ($amaalCategory) {
            $menus->transform(function ($menu) use ($amaalCategory) {
                if ($menu->post_type === 'amaal-namaz' && $amaalCategory->popup_image) {
                    $menu->popup_image = url(
                        Storage::url($amaalCategory->popup_image)
                    );
                }
                return $menu;
            });
        }

        return response()->json([
            'status' => true,
            'hijri_category' => $categoryName,
            // 'amaal_available' => (bool) $amaalCategory,
            'data' => $menus
        ]);
    }
}
