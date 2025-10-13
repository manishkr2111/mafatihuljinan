<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarqueeText;

class MarqueTextController extends Controller
{
    public function index()
    {
        $data = MarqueeText::all();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
