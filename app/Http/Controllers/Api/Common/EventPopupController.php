<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\EventPopup;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Validation\ValidationException;

class EventPopupController extends Controller
{
    public function GetEventPopUp(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|integer|min:1|max:31',
                'month' => 'required|integer|min:1|max:12',
                'language' => [
                    'required',
                    'string',
                    'max:50',
                    'in:' . implode(',', validLanguages()),
                ],
            ]);

            $eventPopups = EventPopup::where('language', $request->language)
                ->where('date', $request->date)
                ->where('month', $request->month)
                ->select('title', 'date', 'month', 'language', 'imgurl')->first();
            if (!$eventPopups) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event Popups not found for this date and month',
                    'data' => [],
                ], 404);
            }
            $eventPopups->imgurl = asset('storage/' . $eventPopups->imgurl);
            return response()->json([
                'status' => true,
                'message' => 'Event Popups fetched successfully',
                'data' => $eventPopups
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => "Something went wrong",
                'data' => [],
            ], 500);
        }
    }
}
