<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\CustomUserPost;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CustomUserPostController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                // 'arabic_content' => 'nullable|string',
                // 'transliteration_content' => 'nullable|string',
                // 'translation_content' => 'nullable|string',
                'language' => ['required', 'string', 'max:100', Rule::in(validLanguages())],
                'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:10240', // max 10MB
            ]);

            $validated['user_id'] = $user->id;
            $audioPath = null;
            if ($request->hasFile('audio')) {
                $audioFile = $request->file('audio');
                $originalName = $audioFile->getClientOriginalName(); // get original file name
                $audioPath = $audioFile->storeAs('audios', $originalName, 'public'); // keep original name
                $validated['audio_url'] = $audioPath;
            }

            $post = CustomUserPost::create($validated);
            $post->audio_url = Storage::disk('public')->url($audioPath);

            return response()->json([
                'status' => true,
                'message' => 'Post created successfully.',
                'data' => $post
            ], 201);
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

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            $post = CustomUserPost::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$post) {
                return response()->json([
                    'status' => false,
                    'message' => 'Post not found or unauthorized.'
                ], 404);
            }

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'arabic_content' => 'nullable|string',
                'transliteration_content' => 'nullable|string',
                'translation_content' => 'nullable|string',
                'language' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:100',
                    Rule::in(validLanguages()),
                ],
            ]);

            $post->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Post updated successfully.',
                'data' => $post
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
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
}
