<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnglishSahifasShlulbayt;
use Illuminate\Support\Facades\Cache;

class EnglishPostController extends Controller
{
    public function SahifasShlulbayt(Request $request)
    {
        $post_type = $request->query('post_typestatus', $request->post_type);
        $posts = EnglishSahifasShlulbayt::where('status', 'published')
            ->get()->makeHidden(['created_at', 'updated_at', 'category_ids']);
        foreach ($posts as $post) {
            $post->cData = [
                'type' => 'arabic',
                'audio_url' => $post->arabic_audio_url,
                'lyrics' => $this->arrangeData($post->arabic_content)
            ];
        }

        return response()->json([
            'status' => true,
            'post_type' => $post_type,
            'message' => 'Posts fetched successfully',
            'data' => $posts
        ]);
    }

    public function arrangeData($content)
    {
        $html = $content; // your HTML content

        // Split by closing </p> tag
        $paragraphs = explode('</p>', $html);

        // Clean each paragraph
        /*$paragraphs = array_map(function ($p) {
            $p = trim($p);              // Remove whitespace
            if ($p === '') return null; // Skip empty paragraphs
            return $p . '</p>';         // Add closing tag back if needed
        }, $paragraphs);
        */
        $paragraphs = array_map(function ($p) {
            $p = trim($p);
            if ($p === '') return null;            // skip empty paragraphs
            $p = preg_replace('/<span.*?<\/span>/is', '', $p); // remove span tags
            $p = strip_tags($p, '<br>');           // remove all other tags except <br>
            return trim($p);
        }, $paragraphs);

        // Remove null/empty entries
        $paragraphs = array_filter($paragraphs);

        dd($paragraphs);
    }
}
