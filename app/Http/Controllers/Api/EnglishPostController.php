<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnglishSahifasAhlulbayt;
use Illuminate\Support\Facades\Cache;

class EnglishPostController extends Controller
{
    public function SahifasAhlulbayt(Request $request)
    {
        $post_type = $request->query('post_typestatus', $request->post_type);
        $posts_old = EnglishSahifasAhlulbayt::where('status', 'published')
            ->get()->makeHidden(['created_at', 'updated_at', 'category_ids']);
        $posts = EnglishSahifasAhlulbayt::whereJsonContains('category_ids', '9')
            ->where('status', 'published')
            ->get()->makeHidden(['created_at', 'updated_at', 'category_ids']);
        $result = [
            'status' => true,
            'post_type' => 'Sahifas Ahlulbayt',
            'message' => 'Posts fetched successfully',
            'data' => []
        ];

        foreach ($posts as $post) {
            // Generate the cData array
            $cData = [
                'type' => 'arabic',
                'audio_url' => $post->arabic_audio_url ?? null,
                'lyrics' => $this->arrangeData($post->arabic_content, 'arabic', false)
            ];

            // Convert post to array and add cData inside
            $postArray = $post->toArray();
            // unset the unnecessary fields
            unset(
                $postArray['arabic_content'],
                $postArray['transliteration_content'],
                $postArray['translation_content'],
                $postArray['english_content'],
                $postArray['sort_number'],
                $postArray['roman_data'],
                $postArray['arabic_audio_url'],
                $postArray['transliteration_audio_url'],
                $postArray['translation_audio_url'],
                $postArray['status'],
            );
            $postArray['cData'][] = $cData;

            $result['data'][] = $postArray;
        }

        return response()->json($result);
    }

    public function arrangeData($content, $type, $enable4line = false , $islyrics = false)
    {
        $html = $content;
        $paragraphs = explode('</p>', $html);
        $paragraphs = array_map(function ($p) {
            $p = trim($p);
            if ($p === '') return null;            // skip empty paragraphs
            $p = preg_replace('/<span.*?<\/span>/is', '', $p); // remove span tags
            $p = strip_tags($p, '<br>');           // remove all other tags except <br>
            return trim($p);
        }, $paragraphs);

        $paragraphs = array_filter($paragraphs);
        $linecount = count($paragraphs);
        $data = [];
        if ($enable4line) {
            foreach ($paragraphs as &$paragraph) {
                $data['time'] = $type;
                $data['arabic'] = [];
                $data['translitration'] = [];
                $data['translation'] = [];
            }
        } else {
            $count = 0;
            if ($islyrics == false) {
                // dd($linecount);
                while ($linecount >=  $count) {
                    $data[] = [
                        'time' => $paragraphs[$count],
                        'arabic' => $paragraphs[$count + 1] ?? '',
                        'translitration' => $paragraphs[$count + 2] ?? '',
                        'translation' => $paragraphs[$count + 3] ?? '',
                        'english' =>  ''
                    ];
                    //dd($paragraphs[$count + 3]);
                    $linecount -= 3;
                    $count += 4;
                }
            }else{
                while ($linecount <=  $count) {
                    $data[] = [
                        'time' => $paragraphs[$count],
                        'arabic' => $paragraphs[$count + 1] ?? '',
                        'translitration' => $paragraphs[$count + 2] ?? '',
                        'translation' => $paragraphs[$count + 3] ?? '',
                        'english' =>  $paragraphs[$count + 4] ?? ''
                    ];
                    //dd($paragraphs[$count + 3]);
                    $linecount -= 4;
                    $count += 4;
                }
            }
        }
        //return $data;
        //return $paragraphs;
        dd($paragraphs);
    }
}
