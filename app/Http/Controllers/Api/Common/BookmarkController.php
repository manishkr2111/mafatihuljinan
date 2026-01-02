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
    public function store_old(Request $request)
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
                'status'  => true,
                'message'  => 'Bookmark processed successfully.',
                'language' => $language,
                'data'     => [
                    'inserted' => $inserted,
                    'skipped'  => $skipped,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            // -----------------------
            // VALIDATION
            // -----------------------
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

                // c_data validation
                'bookmark.*.post_ids.*.c_data' => 'required|array|min:1',
                'bookmark.*.post_ids.*.c_data.*.type' =>
                'required|string|in:arabic,transliteration,translation',

                'bookmark.*.post_ids.*.c_data.*.indexes' => 'required|array|min:1',
                'bookmark.*.post_ids.*.c_data.*.indexes.*' => 'integer',
            ]);

            $user      = Auth::user();
            $language  = $validated['language'];

            $inserted = [];
            $skipped  = [];


            // ------------------------------------------------
            // LOOP THROUGH BOOKMARK GROUPS
            // ------------------------------------------------
            foreach ($validated['bookmark'] as $bookmarkGroup) {

                $postType = $bookmarkGroup['post_type'];
                $postItems = $bookmarkGroup['post_ids'];


                // 1. GET MODEL CLASS BY LANGUAGE
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

                // If model missing
                if (!$modelClass || !class_exists($modelClass)) {
                    $skipped[] = [
                        'post_type' => $postType,
                        'reason'    => 'Invalid post type or model not found'
                    ];
                    continue;
                }


                // ------------------------------------------------
                // LOOP THROUGH ALL POST IDs
                // ------------------------------------------------
                foreach ($postItems as $item) {

                    $postId = $item['post_id'];
                    $cData  = $item['c_data']; // NEW structure


                    // Make sure post exists
                    if (!$modelClass::where('id', $postId)->exists()) {
                        $skipped[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'reason'    => 'Post not found'
                        ];
                        continue;
                    }

                    $formatted = [];

                    foreach ($cData as $row) {
                        $type    = $row['type'];
                        $indexes = $row['indexes'];

                        if (!isset($formatted[$type])) {
                            $formatted[$type] = [];
                        }

                        $formatted[$type] = array_values(array_unique(array_merge(
                            $formatted[$type],
                            $indexes
                        )));
                    }


                    // ------------------------------------------------
                    // CHECK IF BOOKMARK ALREADY EXISTS
                    // ------------------------------------------------
                    $bookmark = Bookmark::where([
                        'user_id'   => $user->id,
                        'post_id'   => $postId,
                        'post_type' => $postType,
                        'language'  => $language,
                    ])->first();


                    // ------------------------------------------------
                    // UPDATE EXISTING BOOKMARK
                    // ------------------------------------------------
                    if ($bookmark) {

                        $existing = json_decode($bookmark->bookmark_indexes, true) ?: [];

                        // Merge type-wise
                        foreach ($formatted as $type => $indexes) {
                            if (!isset($existing[$type])) {
                                $existing[$type] = [];
                            }

                            $existing[$type] = array_values(array_unique(array_merge(
                                $existing[$type],
                                $indexes
                            )));
                        }

                        $bookmark->update([
                            'bookmark_indexes' => json_encode($existing)
                        ]);

                        $inserted[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'bookmark_indexes'   => $existing,
                            'msg'       => 'Updated existing bookmark.'
                        ];
                    } else {

                        Bookmark::create([
                            'user_id'          => $user->id,
                            'post_id'          => $postId,
                            'post_type'        => $postType,
                            'language'         => $language,
                            'bookmark_indexes' => json_encode($formatted),
                        ]);

                        $inserted[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'bookmark_indexes'   => $formatted,
                            'msg'       => 'Bookmark created'
                        ];
                    }
                }
            }

            return response()->json([
                'status'   => true,
                'message'  => 'Bookmark processed successfully.',
                'language' => $language,
                'data'     => [
                    'inserted' => $inserted,
                    'skipped'  => $skipped,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function store_new(Request $request)
    {
        try {
            $validated = $request->validate([
                'bookmarks' => 'required|array|min:1',

                'bookmarks.*.language' => [
                    'required',
                    'string',
                    Rule::in(validLanguages()),
                ],

                'bookmarks.*.postType' => [
                    'required',
                    'string',
                    Rule::in(array_keys(commonPostTypeOptions())),
                ],

                'bookmarks.*.postId' => 'required|integer|min:1',

                'bookmarks.*.cdataType' => [
                    'required',
                    'string',
                    Rule::in(['arabic', 'transliteration', 'translation']),
                ],

                'bookmarks.*.indexes' => 'required|array|min:1',
                'bookmarks.*.indexes.*' => 'required|integer|min:1',
            ]);

            $user = Auth::user();

            foreach ($validated['bookmarks'] as $bm) {

                // Default empty structure
                $data = [
                    'indexes_arabic'           => [],
                    'indexes_transliteration'  => [],
                    'indexes_translation'      => [],
                ];

                // Fill correct field based on cdataType
                if ($bm['cdataType'] === 'arabic') {
                    $data['indexes_arabic'] = $bm['indexes'];
                }
                if ($bm['cdataType'] === 'transliteration') {
                    $data['indexes_transliteration'] = $bm['indexes'];
                }
                if ($bm['cdataType'] === 'translation') {
                    $data['indexes_translation'] = $bm['indexes'];
                }

                // Insert or update existing bookmark
                Bookmark::updateOrCreate(
                    [
                        'user_id'   => $user->id,
                        'post_id'   => $bm['postId'],
                        'post_type' => $bm['postType'],
                        'language'  => $bm['language'],
                    ],
                    $data
                );
            }

            return response()->json([
                'status'  => true,
                'message' => 'Bookmarks saved successfully.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
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
                    'status' => true,
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
                'status' => true,
                'message' => 'bookmark retrieved successfully.',
                'language' => $language,
                'data' => $bookmarkData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removeBookmarkIndex(Request $request)
    {
        try {

            // -----------------------
            // VALIDATION
            // -----------------------
            $validated = $request->validate([
                'language' => [
                    'required',
                    'string',
                    Rule::in(validLanguages()),
                ],

                'bookmark' => 'required|array|min:1',

                'bookmark.*.post_type' => 'required|string|max:255',
                'bookmark.*.post_ids'  => 'required|array|min:1',

                'bookmark.*.post_ids.*.post_id' => 'required|integer',

                // c_data validation (TYPE-AWARE)
                'bookmark.*.post_ids.*.c_data' => 'required|array|min:1',
                'bookmark.*.post_ids.*.c_data.*.type' =>
                'required|string|in:arabic,transliteration,translation',

                'bookmark.*.post_ids.*.c_data.*.indexes' => 'required|array|min:1',
                'bookmark.*.post_ids.*.c_data.*.indexes.*' => 'integer',
            ]);

            $user     = Auth::user();
            $language = $validated['language'];

            $removed  = [];
            $notFound = [];
            $skipped  = [];

            // ------------------------------------------------
            // LOOP THROUGH BOOKMARK GROUPS
            // ------------------------------------------------
            foreach ($validated['bookmark'] as $bookmarkGroup) {

                $postType  = $bookmarkGroup['post_type'];
                $postItems = $bookmarkGroup['post_ids'];

                // ------------------------------------------------
                // GROUP BY post_id (PREVENT DUPLICATES)
                // ------------------------------------------------
                $groupedPosts = [];

                foreach ($postItems as $item) {
                    $pid = $item['post_id'];

                    if (!isset($groupedPosts[$pid])) {
                        $groupedPosts[$pid] = [];
                    }

                    $groupedPosts[$pid] = array_merge(
                        $groupedPosts[$pid],
                        $item['c_data']
                    );
                }

                // ------------------------------------------------
                // PROCESS EACH UNIQUE POST
                // ------------------------------------------------
                foreach ($groupedPosts as $postId => $cData) {

                    // Fetch bookmark
                    $bookmark = Bookmark::where([
                        'user_id'   => $user->id,
                        'post_id'   => $postId,
                        'post_type' => $postType,
                        'language'  => $language,
                    ])->first();

                    if (!$bookmark) {
                        $notFound[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'reason'    => 'Bookmark not found'
                        ];
                        continue;
                    }

                    $existing = json_decode($bookmark->bookmark_indexes, true) ?: [];

                    // ------------------------------------------------
                    // REMOVE INDEXES TYPE-WISE
                    // ------------------------------------------------
                    foreach ($cData as $row) {

                        $type    = $row['type'];
                        $indexes = $row['indexes'];

                        if (!isset($existing[$type])) {
                            continue;
                        }

                        $existing[$type] = array_values(array_diff(
                            $existing[$type],
                            $indexes
                        ));

                        // Remove empty type
                        if (empty($existing[$type])) {
                            unset($existing[$type]);
                        }
                    }

                    // ------------------------------------------------
                    // DELETE OR UPDATE BOOKMARK
                    // ------------------------------------------------
                    if (empty($existing)) {

                        $bookmark->delete();

                        $removed[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'msg'       => 'Bookmark deleted (no indexes left)'
                        ];
                    } else {

                        $bookmark->update([
                            'bookmark_indexes' => json_encode($existing)
                        ]);

                        $removed[] = [
                            'post_type' => $postType,
                            'post_id'   => $postId,
                            'remaining_bookmark_indexes' => $existing,
                            'msg'       => 'Indexes removed successfully'
                        ];
                    }
                }
            }

            return response()->json([
                'status'   => true,
                'message'  => 'Bookmark indexes processed successfully.',
                'language' => $language,
                'data'     => [
                    'removed'   => $removed,
                    'not_found' => $notFound,
                ],
            ], 200);
        } catch (ValidationException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
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
