<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarqueeText;

class MarqueTextController extends Controller
{
    public function index(Request $request)
    {
        $language = $request->get('language', 'english'); // default to English
        try {
            $marqueeTexts = MarqueeText::where('language', $language)
                ->select('text')
                ->get();
            return response()->json([
                'status' => true,
                'message' => 'Marquee texts fetched successfully',
                'language' => $language,
                'data' => $marqueeTexts     
            ],200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching marquee texts'
            ], 500);
        }
    }
}
