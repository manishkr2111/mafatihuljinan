<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Bookmark;
use App\Models\Common\CustomUserPost;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
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
                'bookmark' => 'required|array|min:1',

                'bookmark.*.post_type' => 'required|string|max:255',

                // post_ids contain objects now
                'bookmark.*.post_ids' => 'required|array|min:1',

                'bookmark.*.post_ids.*.post_id' => 'required|integer',
                'bookmark.*.post_ids.*.indexes' => 'required|array|min:1',
                'bookmark.*.post_ids.*.indexes.*' => 'integer',
            ]);

            $user = Auth::user();
            $language = $validated['language'];

            $inserted = [];
            $skipped = [];

            foreach ($validated['bookmark'] as $bookmarkGroup) {

                $postType = $bookmarkGroup['post_type'];
                $postItems = $bookmarkGroup['post_ids'];

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

                if (!$modelClass || !class_exists($modelClass)) {
                    $skipped[] = [
                        'post_type' => $postType,
                        'reason' => 'Invalid post type or model not found',
                    ];
                    continue;
                }

                foreach ($postItems as $item) {

                    $postId = $item['post_id'];
                    $indexes = $item['indexes'];

                    // Check if post exists
                    if (!$modelClass::where('id', $postId)->exists()) {
                        $skipped[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'reason' => 'Post not found',
                        ];
                        continue;
                    }

                    // Check if bookmark exists
                    $bookmark = Bookmark::where([
                        'user_id'  => $user->id,
                        'post_id'  => $postId,
                        'post_type' => $postType,
                        'language' => $language,
                    ])->first();

                    if ($bookmark) {
                        // merge indexes
                        $existingIndexes = json_decode($bookmark->bookmark_indexes, true) ?: [];

                        $mergedIndexes = array_values(array_unique(array_merge($existingIndexes, $indexes)));

                        $bookmark->update([
                            'bookmark_indexes' => json_encode($mergedIndexes)
                        ]);

                        $inserted[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'indexes'   => $mergedIndexes,
                            'msg'       => 'Updated existing bookmark'
                        ];
                    } else {
                        // create new bookmark
                        Bookmark::create([
                            'user_id'         => $user->id,
                            'post_id'         => $postId,
                            'post_type'       => $postType,
                            'language'        => $language,
                            'bookmark_indexes' => json_encode($indexes),
                        ]);

                        $inserted[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'indexes'   => $indexes,
                            'msg'       => 'Bookmark created'
                        ];
                    }
                }
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Bookmark processed successfully.',
                'language' => $language,
                'data'     => [
                    'inserted' => $inserted,
                    'skipped'  => $skipped,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }


    //// get all bookmark post

    public function getAllBookmark(Request $request)
    {
        try {
            $user = Auth::user();

            //  Optional: allow filtering by language
            $language = $request->get('language');

            $query = Bookmark::where('user_id', $user->id)->where('language', $language);

            $bookmark = $query->get();

            if ($bookmark->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No bookmark found.',
                    'data' => [],
                ], 200);
            }

            $data = [];

            foreach ($bookmark->groupBy(['language', 'post_type']) as $lang => $types) {
                foreach ($types as $postType => $bookmarkItems) {

                    // Select correct model for this language
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

                    if (!$modelClass || !class_exists($modelClass)) {
                        continue;
                    }

                    $postIds = $bookmarkItems->pluck('post_id')->toArray();

                    // Fetch post details safely (ignore missing)
                    $posts = $modelClass::whereIn('id', $postIds)
                        ->get(['id', 'title']);

                    $indexesMap = $bookmarkItems->pluck('bookmark_indexes', 'post_id')->toArray();
                    $posts = $posts->map(function ($p) use ($indexesMap) {
                        $p->bookmark_indexes = json_decode($indexesMap[$p->id] ?? '[]', true);
                        return $p;
                    });
                    $bookmarkData[] = [
                        'post_type' => $postType,
                        'posts' => $posts,
                    ];
                }
            }
            return response()->json([
                'success' => true,
                'message' => 'bookmark retrieved successfully.',
                'language' => $language,
                'data' => $bookmarkData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeBookmarkIndex(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'language' => [
                    'required',
                    'string',
                    Rule::in(validLanguages()),
                ],
                'bookmark' => 'required|array|min:1',

                'bookmark.*.post_type' => 'required|string|max:255',
                'bookmark.*.post_ids' => 'required|array|min:1',

                'bookmark.*.post_ids.*.post_id' => 'required|integer',
                'bookmark.*.post_ids.*.indexes' => 'required|array|min:1',
                'bookmark.*.post_ids.*.indexes.*' => 'integer',
            ]);

            $user = Auth::user();
            $language = $validated['language'];

            $removed = [];
            $notFound = [];
            $skipped = [];

            foreach ($validated['bookmark'] as $bookmarkGroup) {

                $postType = $bookmarkGroup['post_type'];
                $postItems = $bookmarkGroup['post_ids'];

                foreach ($postItems as $item) {

                    $postId = $item['post_id'];
                    $indexesToRemove = $item['indexes'];

                    // Fetch the bookmark
                    $bookmark = Bookmark::where([
                        'user_id' => $user->id,
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'language' => $language,
                    ])->first();

                    if (!$bookmark) {
                        $notFound[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'reason' => 'Bookmark not found'
                        ];
                        continue;
                    }

                    $existing = json_decode($bookmark->bookmark_indexes, true) ?: [];

                    // Remove only given indexes
                    $updated = array_values(array_diff($existing, $indexesToRemove));

                    if (empty($updated)) {
                        // No indexes left -> remove whole bookmark entry
                        $bookmark->delete();

                        $removed[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'removed_indexes' => $indexesToRemove,
                            'msg' => 'Bookmark deleted (no indexes left)'
                        ];
                    } else {
                        // Update remaining indexes
                        $bookmark->update([
                            'bookmark_indexes' => json_encode($updated)
                        ]);

                        $removed[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'removed_indexes' => $indexesToRemove,
                            'remaining_indexes' => $updated,
                            'msg' => 'Indexes removed successfully'
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Bookmark indexes processed successfully.',
                'language' => $language,
                'data' => [
                    'removed' => $removed,
                    'not_found' => $notFound,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /// delete bookmark post
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
                'bookmark' => 'required|array|min:1',
                'bookmark.*.post_type' => 'required|string|max:255',
                'bookmark.*.post_ids' => 'required|array|min:1',
                'bookmark.*.post_ids.*' => 'integer',
            ]);

            $user = Auth::user();
            $language = $validated['language'];

            $deleted = [];
            $notFound = [];

            foreach ($validated['bookmark'] as $bookmarkGroup) {
                $postType = $bookmarkGroup['post_type'];
                $postIds = $bookmarkGroup['post_ids'];

                foreach ($postIds as $postId) {
                    $bookmark = Bookmark::where([
                        'user_id' => $user->id,
                        'post_id' => $postId,
                        'post_type' => $postType,
                        'language' => $language,
                    ])->first();

                    if ($bookmark) {
                        $bookmark->delete();
                        $deleted[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                        ];
                    } else {
                        $notFound[] = [
                            'post_type' => $postType,
                            'post_id' => $postId,
                            'reason' => 'bookmark not found',
                        ];
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'bookmark deleted successfully.',
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
