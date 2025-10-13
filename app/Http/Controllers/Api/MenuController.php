<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function getContent(Request $request)
    {
        $language = $request->get('language', 'english'); // default to English

        $menus = Menu::where('language', $language)->orderBy('sort_number')->get();

        return response()->json([
            'success' => true,
            'language' => $language,
            'menus' => $menus
        ]);
    }
}
