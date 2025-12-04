<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\UserNotepad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserNotepadController extends Controller
{
    /**
     * Retrieve all notes for the authenticated user, filtered by language.
     */
    public function index(Request $request)
    {
        $language = $request->language;

        // Validate language using Rule::in
        $validator = Validator::make($request->all(), [
            'language' => ['required', 'string', Rule::in(validLanguages())],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $notes = UserNotepad::where('user_id', Auth::id())
            ->where('language', $language)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Notes retrieved successfully.',
            'language' => $language,
            'data' => $notes,
        ]);
    }

    /**
     * Create a new note for the authenticated user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'    => 'required|string|max:255',
            'content'  => 'nullable|string',
            'language' => ['required', 'string', Rule::in(validLanguages())],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $note = UserNotepad::create([
            'user_id'  => Auth::id(),
            'title'    => $validated['title'],
            'content'  => $validated['content'] ?? null,
            'language' => $validated['language'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Note created successfully.',
            'data'    => $note,
        ], 201);
    }

    /**
     * Update an existing note.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|integer|max:255',
            'language' => ['required', 'string', Rule::in(validLanguages())],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $id = $request->id;
        $language = $request->language;
        $note = UserNotepad::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('language', $language)
            ->first();

        if (!$note) {
            return response()->json([
                'status' => false,
                'message' => 'Note not found or unauthorized.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'    => 'sometimes|required|string|max:255',
            'content'  => 'nullable|string',
            'language' => ['sometimes', 'string', Rule::in(validLanguages())],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $note->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Note updated successfully.',
            'data'    => $note,
        ]);
    }

    /**
     * Delete a note by ID.
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'required|integer|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $id = $request->id;
        $note = UserNotepad::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$note) {
            return response()->json([
                'status' => false,
                'message' => 'Note not found or unauthorized.',
            ], 404);
        }
        $note->delete();
        return response()->json([
            'status' => true,
            'message' => 'Note deleted successfully.',
        ]);
    }
}
