<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function getContent(Request $request)
    {
        $language = $request->get('language', 'english'); // default to English
        try {
            $menus = Menu::where('language', $language)->orderBy('sort_number')
                ->select('id', 'menu_name', 'post_type','last_data_updated_at')
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
}
