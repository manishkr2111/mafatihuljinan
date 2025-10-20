<?php

namespace App\Http\Controllers\Api\English;

use App\Http\Controllers\Controller;
use App\Models\EnglishCategory;
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

    public function arrangeData($content, $type, $enable4line = false, $islyrics = false)
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
            } else {
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

    public function DuaData(Request $request)
    {
        $post_type = $request->query('post_type', $request->post_type);
        $parent_category_id = $request->query('parent_category_id', $request->parent_category_id);
        try {
            if ($parent_category_id == null) {
                $parent_category = EnglishCategory::where('post_type', $post_type)
                    ->whereNull('parent_id')
                    ->select('id', 'name', 'slug', 'parent_id')
                    ->get();
            } else {
                $parent_category = EnglishCategory::where('post_type', $post_type)
                    ->where('parent_id', $parent_category_id)
                    ->select('id', 'name', 'slug', 'parent_id')
                    ->get();
            }
            $model = getEnglishModel($post_type);
            if (!$model) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid post type',
                    'data' => []
                ], 400);
            }
            if ($parent_category_id == null) {
                $posts = $model::where('status', 'published')
                    ->select('id', 'title')
                    ->where('category_ids', '[]')
                    ->get();
                if (auth()->check()) {
                    isFavoritePosts('english', $post_type, $posts);
                } else {
                    // If user not logged in, default is_fav to false
                    $posts->map(function ($post) {
                        $post->is_fav = false;
                        return $post;
                    });
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data fetched successfully',
                    'categories' => $parent_category,
                    'posts' => $posts,
                ]);
            }
            $posts = $model::where('status', 'published')
                ->whereJsonContains('category_ids', (string)$parent_category_id)
                ->select('id', 'title')
                ->get();
            if (auth()->check()) {
                isFavoritePosts('english', $post_type, $posts);
            } else {
                // If user not logged in, default is_fav to false
                $posts->map(function ($post) {
                    $post->is_fav = false;
                    return $post;
                });
            }
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'categories' => $parent_category,
                'posts' => $posts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }


    public function singlePostDate(Request $request, $id)
    {
        $post_type = $request->query('post_type', $request->post_type);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid post type',
                'data' => null
            ], 400);
        }
        $post = $model::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found',
                'data' => null
            ], 404);
        }
        if ($post->status != 'published') {
            return response()->json([
                'status' => false,
                'message' => 'Post not published',
                'data' => null
            ], 403);
        }

        // Hide unnecessary fields
        $post->makeHidden(['created_at', 'updated_at', 'category_ids']);

        return response()->json([
            'status' => true,
            'message' => 'Post fetched successfully',
            'data' => $post
        ]);
    }

    public function singlepostdata(Request $request)
    {
        $post_type = $request->query('post_type', $request->post_type);
        $model = getEnglishModel($post_type);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid post type',
                'data' => null
            ], 400);
        }
        $post = $model::where('id', $request->id)
            ->where('status', 'published')
            ->first()->makeHidden(['created_at', 'updated_at', 'category_ids']);
        $result = [
            'status' => true,
            'post_type' => 'Sahifas Ahlulbayt',
            'message' => 'Posts fetched successfully',
            'data' => []
        ];

        // Generate the cData array
        $cData = [];
        $cData[] = [
            'type' => 'arabic',
            'audio_url' => $post->arabic_audio_url ?? null,
            'islrc' => $post->arabic_islrc ?? null,
            'lyrics' => $this->arrangeDatasinglepost(
                $post->arabic_content,
                'arabic',
                $post->arabic_4line ?? false,
                $post->arabic_islrc ?? false
            ),
        ];
        $cData[] = [
            'type' => 'Transliteration',
            'audio_url' => $post->transliteration_audio_url ?? null,
            'islrc' => $post->transliteration_islrc ?? null,
            'lyrics' => $this->arrangeDatasinglepost(
                $post->transliteration_content,
                'transliteration',
                $post->transliteration_4line ?? false,
                $post->transliteration_islrc ?? false
            )
        ];
        $cData[] = [
            'type' => 'Translation',
            'audio_url' => $post->translation_audio_url ?? null,
            'islrc' => $post->translation_islrc ?? null,
            'lyrics' => $this->arrangeDatasinglepost(
                $post->translation_content,
                'translation',
                $post->translation_4line ?? false,
                $post->translation_islrc ?? false
            )
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
            $postArray['arabic_islrc'],
            $postArray['arabic_4line'],
            $postArray['transliteration_islrc'],
            $postArray['transliteration_4line'],
            $postArray['translation_islrc'],
            $postArray['translation_4line'],
        );
        $postArray['cData'] = $cData;

        $result['data'][] = $postArray;

        return response()->json($result);
    }

    public function arrangeDatasinglepost($content, $type, $enable4line = false, $islyrics = false)
    {
        $html = $content;
        //$paragraphs = explode('</p>', $html);
        $paragraphs = explode(PHP_EOL, $html);
        $paragraphs = array_map(function ($p) {
            $p = trim($p);
            if ($p === '') return null;            // skip empty paragraphs
            $p = preg_replace('/<span.*?<\/span>/is', '', $p); // remove span tags
            $p = strip_tags($p, '<br>');           // remove all other tags except <br>
            return trim($p);
        }, $paragraphs);

        //$paragraphs = explode(PHP_EOL, $html);

        $paragraphs = array_filter($paragraphs);
        $linecount = count($paragraphs);
        $data = [];
        //dd($paragraphs);
        if ($paragraphs) {
            if ($enable4line) {
                $count = 0;
                if ($islyrics == 0) {
                    while ($linecount >  0) {
                        $data[] = [
                            'time' => '',
                            'arabic' => $paragraphs[$count] ?? '',
                            'translitration' => $paragraphs[$count + 1] ?? '',
                            'translation' => $paragraphs[$count + 2] ?? '',
                            'english' =>  $paragraphs[$count + 3] ?? '',
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 4;
                        $count += 4;
                    }
                } else {
                    while ($linecount > 0) {
                        $data[] = [
                            'time' => $paragraphs[$count],
                            'arabic' => $paragraphs[$count + 1] ?? '',
                            'translitration' => $paragraphs[$count + 2] ?? '',
                            'translation' => $paragraphs[$count + 3] ?? '',
                            'english' =>  $paragraphs[$count + 4] ?? ''
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 5;
                        $count += 5;
                    }
                }
            } else {
                $count = 0;
                if ($islyrics == false) {
                    // dd($linecount);
                    while ($linecount > 0) {
                        $data[] = [
                            'time' => '',
                            'arabic' => $paragraphs[$count] ?? '',
                            'translitration' => $paragraphs[$count + 1] ?? '',
                            'translation' => $paragraphs[$count + 2] ?? '',
                            'english' => ''
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 3;
                        $count += 3;
                    }
                } else {
                    while ($linecount > 0) {
                        $data[] = [
                            'time' => $paragraphs[$count],
                            'arabic' => $paragraphs[$count + 1] ?? '',
                            'translitration' => $paragraphs[$count + 2] ?? '',
                            'translation' => $paragraphs[$count + 3] ?? '',
                            'english' =>  ''
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 4;
                        $count += 4;
                    }
                }
            }
        }
        return $data;
        //return $paragraphs;
        dd($paragraphs);
    }
}
