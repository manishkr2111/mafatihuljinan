<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Favorite;
use App\Models\Common\CustomUserPost;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'language' => [
                    'required',
                    'string',
                    Rule::in(validLanguages()),
                ],
                'favorites' => 'required|array|min:1',
                'favorites.*.post_type' => 'required|string|max:255',
                'favorites.*.post_ids' => 'required|array|min:1',
                'favorites.*.post_ids.*' => 'integer',
            ]);

            $user = Auth::user();
            $language = $validated['language'];
            $inserted = [];
            $skipped = [];

            foreach ($validated['favorites'] as $favGroup) {
                $postType = $favGroup['post_type'];
                $postIds = $favGroup['post_ids'];

                // Select correct model based on language
                if ($language === 'gujarati') {
                    $modelClass = getGujaratiModel($postType);
                } elseif ($language === 'english') {
                    $modelClass = getEnglishModel($postType);
                } elseif ($language === 'hindi') {
                    $modelClass = getHindiModel($postType);
                } elseif ($language === 'urdu') {
                    $modelClass = getUrduModel($postType);
                } elseif ($language === 'roman urdu') {
                    $modelClass = getRomanUrduModel($postType);
                } elseif ($language === 'swahili') {
                    $modelClass = getSwahiliModel($postType);
                } elseif ($language === 'french') {
                    $modelClass = getFrenchModel($postType);
                } else {
                    $modelClass = null;
                }

                // Skip if invalid post type or language
                if (!$modelClass || !class_exists($modelClass)) {
                    $skipped[] = [
                        'post_type' => $postType,
                        'reason' => 'Invalid post type or language model not found',
                    ];
                    continue;
                }

                foreach ($postIds as $postId) {
                    // Check if post exists
                    $postExists = $modelClass::where('id', $postId)->exists();

                    if (!$postExists) {
                        $skipped[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'reason' => 'Post not found',
                        ];
                        continue;
                    }

                    // Create favorite only if it doesn’t exist
                    Favorite::firstOrCreate([
                        'user_id' => $user->id,
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'language' => $language,
                    ]);

                    $inserted[] = [
                        'post_type' => $postType,
                        'post_id' => $postId,
                    ];
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Favorites processed successfully.',
                'language' => $language,
                'data' => [
                    'inserted' => $inserted,
                    'skipped' => $skipped,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //// get all fav post

    public function getAllFavorites(Request $request)
    {
        try {
            $user = Auth::user();

            //  Optional: allow filtering by language
            $language = $request->get('language');

            $query = Favorite::where('user_id', $user->id)->where('language', $language);

            $favorites = $query->get();


            $data = [];
            $favoritesData = null;
            if ($favorites->isEmpty()) {
                /*return response()->json([
                    'status' => true,
                    'message' => 'No favorites found.',
                    'data' => [],
                ], 200);
				*/
            } else {
                foreach ($favorites->groupBy(['language', 'post_type']) as $lang => $types) {
                    foreach ($types as $postType => $favItems) {

                        // Select correct model for this language
                        if ($lang === 'gujarati') {
                            $modelClass = getGujaratiModel($postType);
                        } elseif ($lang === 'english') {
                            $modelClass = getEnglishModel($postType);
                        } else {
                            $modelClass = null;
                        }

                        if (!$modelClass || !class_exists($modelClass)) {
                            continue;
                        }

                        $postIds = $favItems->pluck('post_id')->toArray();

                        // Fetch post details safely (ignore missing)
                        $posts = $modelClass::whereIn('id', $postIds)
                            ->get(['id', 'title',]);

                        $favoritesData[] = [
                            'post_type' => $postType,
                            'posts' => $posts,
                        ];
                    }
                }
            }
            // Fetch custom posts
            $customPosts = CustomUserPost::where('user_id', $user->id)
                ->where('language', 'like', $language)
                ->get(['id', 'title', 'arabic_content', 'transliteration_content', 'translation_content']);

            // Prepare final structured response
            $data = [
                'favorites' => $favoritesData,
                'custom_posts' => $customPosts,
            ];
            return response()->json([
                'status' => true,
                'message' => 'Favorites retrieved successfully.',
                'language' => $language,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /// delete fav post
    public function destroy(Request $request)
    {
        try {
            // ✅ Validation without distinct rule
            $validated = $request->validate([
                'language' => [
                    'required',
                    'string',
                    Rule::in(validLanguages()),
                ],
                'favorites' => 'required|array|min:1',
                'favorites.*.post_type' => 'required|string|max:255',
                'favorites.*.post_ids' => 'required|array|min:1',
                'favorites.*.post_ids.*' => 'integer',
            ]);

            $user = Auth::user();
            $language = $validated['language'];

            $deleted = [];
            $notFound = [];

            foreach ($validated['favorites'] as $favGroup) {
                $postType = $favGroup['post_type'];
                $postIds = $favGroup['post_ids'];

                foreach ($postIds as $postId) {
                    $favorite = Favorite::where([
                        'user_id' => $user->id,
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'language' => $language,
                    ])->first();

                    if ($favorite) {
                        $favorite->delete();
                        $deleted[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                        ];
                    } else {
                        $notFound[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'reason' => 'Favorite not found',
                        ];
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Favorites deleted successfully.',
                'language' => $language,
                'data' => [
                    'deleted' => $deleted,
                    'skipped' => $notFound,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'data' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
