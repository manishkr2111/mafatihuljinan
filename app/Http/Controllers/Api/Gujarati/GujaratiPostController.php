<?php

namespace App\Http\Controllers\Api\Gujarati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gujarati\Category;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\PersonalAccessToken;

class GujaratiPostController extends Controller
{
    public function DuaData(Request $request)
    {
        $token = $request->bearerToken();
        $user = null;

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken) {
                $user = $accessToken->tokenable; // this is the User model
            }
        }
        $post_type = $request->query('post_type', $request->post_type);
        $parent_category_id = $request->query('parent_category_id', $request->parent_category_id);
        try {
            if ($parent_category_id == null) {
                $parent_category = Category::where('post_type', $post_type)
                    ->whereNull('parent_id')
                    ->select('id', 'name', 'slug', 'parent_id', 'post_type')
                    ->get();
            } else {
                $parent_category = Category::where('post_type', $post_type)
                    ->where('parent_id', $parent_category_id)
                    ->select('id', 'name', 'slug', 'parent_id', 'post_type')
                    ->get();
            }
            $model = getGujaratiModel($post_type);
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
                if ($posts) {
                    $posts = $posts->map(function ($post) use ($post_type) {
                        $post->post_type = $post_type;
                        $post->parent_category_id = "";
                        return $post;
                    });
                }
                if ($user) {
                    isFavoritePosts($post_type, $posts, $user, 'english', $parent_category_id);
                } else {
                    // If user not logged in, default is_fav to false
                    $posts->map(function ($post) {
                        $post->parent_category_id = "";
                        $post->is_fav = false;
                        return $post;
                    });
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data fetched successfully',
                    'data' => [
                        'categories' => $parent_category,
                        'posts' => $posts,
                    ]
                ]);
            }
            $posts = $model::where('status', 'published')
                ->whereJsonContains('category_ids', (string)$parent_category_id)
                ->select('id', 'title')
                ->get();
            if ($user) {
                isFavoritePosts($post_type, $posts, $user, 'gujarati', $parent_category_id);
            } else {
                // If user not logged in, default is_fav to false
                $posts->map(function ($post) use ($post_type, $parent_category_id) {
                    $post->post_type = $post_type;
                    $post->parent_category_id = (int)$parent_category_id;
                    $post->is_fav = false;
                    return $post;
                });
            }
            return response()->json([
                'status' => true,
                'message' => 'Data fetched successfully',
                'data' => [
                    'categories' => $parent_category,
                    'posts' => $posts,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function singlepostdata(Request $request)
    {
        $post_type = $request->query('post_type', $request->post_type);
        $model = getGujaratiModel($post_type);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid post type',
                'data' => null
            ], 400);
        }
        $post = $model::where('id', $request->id)
            ->where('status', 'published')
            ->first();
        if ($post) {
            if ($post->redirect_deep_link && $post->redirect_deeplink_post_type) {
                $post_type = $post->redirect_deeplink_post_type;
                $model = getGujaratiModel($post->redirect_deeplink_post_type);
                $post = $model::where('slug', $post->redirect_deep_link)
                    ->where('status', 'published')
                    ->first();
                if ($post) {
                    $post->redirect_deeplink_data = true;
                }
            }
            $post->makeHidden(['wordpress_id', 'created_at', 'updated_at', 'category_ids', 'sort_number', 'redirect_deep_link', 'redirect_deeplink_post_type']);
            $post->post_type = $post_type;
        } else {
            // handle the case where the post is not found
            return response()->json([
                'status' => false,
                'message' => 'Post not found or not published',
                'data' => []
            ], 404);
        }
        $result = [
            'status' => true,
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

        // Next post data
        $nextPost = null;
        $postArray['next_post_id'] = null;
        if (isset($postArray['next_post_url'])) {
            $nextPost = $model::where('slug', $postArray['next_post_url'])
                ->where('status', 'published')
                ->first();
            if ($nextPost) {
                $postArray = array_merge(
                    $postArray,
                    [
                        'next_post_title' =>  $postArray['next_post_title'] ?? $nextPost->title ?? '',
                        'next_post_id' => $nextPost->id,
                    ]
                );
            }
        }

        $postArray['cData'] = $cData;
        if ($post_type == 'surah' && isset($postArray['word_meanings'])) {
            $postArray['WordMeanings'] = json_decode($postArray['word_meanings'], true) ?? [];
        }
        // dd($nextPost);
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
            $postArray['word_meanings'],
            $postArray['next_post_url'],
            $postArray['status'],
        );

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
            if ($p === '') return null;                         // skip empty paragraphs
            $p = preg_replace('/<span.*?<\/span>/is', '', $p);  // remove span tags
            $p = strip_tags($p, '<br>');                        // remove all other tags except <br>
            $p = str_replace(["&nbsp;", "\u{00A0}"], '', $p);   // remove &nbsp; or unicode non-breaking space
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

                        $TafsirandSearch = $this->extractTafsirandSearch($paragraphs[$count]);
                        $tafsir = $TafsirandSearch['tafsir'];
                        $search_text = $TafsirandSearch['search_text'];

                        $data[] = [
                            'time' => '',
                            'arabic' => $paragraphs[$count] ?? '',
                            'translitration' => $paragraphs[$count + 1] ?? '',
                            'translation' => $paragraphs[$count + 2] ?? '',
                            'english' =>  $paragraphs[$count + 3] ?? '',
                            'tafsir' => $tafsir,
                            'search_text' => $search_text
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 4;
                        $count += 4;
                    }
                } else {
                    while ($linecount > 0) {

                        $TafsirandSearch = $this->extractTafsirandSearch($paragraphs[$count + 1]);
                        $tafsir = $TafsirandSearch['tafsir'];
                        $search_text = $TafsirandSearch['search_text'];

                        $data[] = [
                            'time' => $paragraphs[$count],
                            'arabic' => $paragraphs[$count + 1] ?? '',
                            'translitration' => $paragraphs[$count + 2] ?? '',
                            'translation' => $paragraphs[$count + 3] ?? '',
                            'english' =>  $paragraphs[$count + 4] ?? '',
                            'tafsir' => $tafsir,
                            'search_text' => $search_text
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
                        $TafsirandSearch = $this->extractTafsirandSearch($paragraphs[$count]);
                        $tafsir = $TafsirandSearch['tafsir'];
                        $search_text = $TafsirandSearch['search_text'];

                        $data[] = [
                            'time' => '',
                            'arabic' => $paragraphs[$count] ?? '',
                            'translitration' => $paragraphs[$count + 1] ?? '',
                            'translation' => $paragraphs[$count + 2] ?? '',
                            'english' => '',
                            'tafsir' => $tafsir,
                            'search_text' => $search_text
                        ];
                        //dd($paragraphs[$count + 3]);
                        $linecount -= 3;
                        $count += 3;
                    }
                } else {
                    while ($linecount > 0) {

                        $TafsirandSearch = $this->extractTafsirandSearch($paragraphs[$count + 1]);
                        $tafsir = $TafsirandSearch['tafsir'];
                        $search_text = $TafsirandSearch['search_text'];

                        $data[] = [
                            'time' => $paragraphs[$count],
                            'arabic' => $paragraphs[$count + 1] ?? '',
                            'translitration' => $paragraphs[$count + 2] ?? '',
                            'translation' => $paragraphs[$count + 3] ?? '',
                            'english' =>  '',
                            'tafsir' => $tafsir,
                            'search_text' => $search_text
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

    private function extractTafsirandSearch(&$line)
    {
        $tafsir = "";
        $search_text = "";

        // Extract tafsir
        if (preg_match('/tafsir\s*=\s*(\d+)/i', $line, $match)) {
            $tafsir = $match[1];
            $line = preg_replace('/["\'\s-]*tafsir\s*=\s*\d+["\'\s-]*/i', '', $line);
        }

        // Extract search_text
        if (preg_match('/search_text\s*=\s*\[([^\]]+)\]/i', $line, $match)) {
            $search_text = array_map('trim', explode(',', $match[1]));
            $line = preg_replace('/["\'\s-]*search_text\s*=\s*\[[^\]]+\]["\'\s-]*/i', '', $line);
        }

        $line = trim($line);

        return ['tafsir' => $tafsir, 'search_text' => $search_text];
    }
}
