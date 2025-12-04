<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\TafsirData;

class TafsirDataController extends Controller
{
    public function TafsirData($id)
    {

        $tafsir = TafsirData::findorfail($id);
        unset(
            $tafsir->created_at,
            $tafsir->updated_at,
            $tafsir->language
        );
        return response()->json([
            'status' => true,
            'message' => 'Tafsir Data fetched successfully',
            'data' => $tafsir,
        ]);
    }
}
