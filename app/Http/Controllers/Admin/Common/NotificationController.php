<?php

namespace App\Http\Controllers\Admin\Common;

use App\Http\Controllers\Controller;
use App\Models\Common\UserFcmToken;
use App\Models\Common\NotificationSchedule;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase; // ✅ Use the facade
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Illuminate\Support\Facades\Validator;


class NotificationController extends Controller
{
    /**
     * Send notification to a single FCM token
     */
    public function sendToToken(
        string $fcmToken,
        string $title,
        string $message,
        ?string $imageUrl = null,
        array $extraData = []
    ): bool {

        $messaging = Firebase::messaging(); // Get messaging via Facade

        $notification = Notification::create($title, $message);

        if (!empty($imageUrl)) {
            $notification = $notification->withImageUrl($imageUrl);
        }


        $cloudMessage = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification($notification)
            ->withData(array_merge([
                'message' => $message,
                'image_url' => $imageUrl,
            ], $extraData))
            ->withApnsConfig(ApnsConfig::fromArray([
                'headers' => ['apns-priority' => '10'],
                'payload' => [
                    'aps' => [
                        'alert' => ['title' => $title, 'body' => $message],
                        'sound' => 'default',
                    ],
                ],
            ]))
            ->withAndroidConfig(AndroidConfig::fromArray([
                'priority' => 'high',
                'notification' => [
                    'title' => $title,
                    'body'  => $message,
                    'sound' => 'default',
                ],
            ]));

        try {
            $messaging->send($cloudMessage);
            return true;
        } catch (MessagingException | FirebaseException $e) {

            // Deactivate invalid tokens
            if (
                str_contains($e->getMessage(), 'Requested entity was not found') ||
                str_contains($e->getMessage(), 'registration-token-not-registered')
            ) {
                UserFcmToken::where('fcm_token', $fcmToken)
                    ->update(['is_active' => false]);
            }

            Log::error('FCM Send Error', [
                'token' => $fcmToken,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send notification to all active tokens of a user
     */
    public function sendToUser(
        int $userId,
        string $title,
        string $message,
        ?string $imageUrl = null,
        array $extraData = []
    ): int {
        $tokens = UserFcmToken::active()
            ->where('user_id', $userId)
            ->pluck('fcm_token');

        $successCount = 0;
        foreach ($tokens as $token) {
            if ($this->sendToToken($token, $title, $message, $imageUrl, $extraData)) {
                $successCount++;
            }
        }
        return $successCount;
    }

    /**
     * Send notification to all active users
     */
    public function sendToAll(
        string $title,
        string $message,
        ?string $imageUrl = null,
        array $extraData = []
    ): int {
        $tokens = UserFcmToken::active()->pluck('fcm_token');

        $successCount = 0;
        foreach ($tokens as $token) {
            if ($this->sendToToken($token, $title, $message, $imageUrl, $extraData)) {
                $successCount++;
            }
        }
        return $successCount;
    }


    public function sendToMultipleTokens(
        array $tokens,
        string $title,
        string $message,
        ?string $imageUrl = null,
        array $extraData = []
    ): array {

        $messaging = Firebase::messaging();

        $notification = Notification::create($title, $message);

        if (!empty($imageUrl)) {
            $notification = $notification->withImageUrl($imageUrl);
        }

        $data = array_merge(['message' => $message], $extraData);

        if (!empty($imageUrl)) {
            $data['image_url'] = $imageUrl;
        }

        $messagePayload = CloudMessage::new()
            ->withNotification($notification)
            ->withData($data)
            ->withAndroidConfig(AndroidConfig::fromArray([
                'priority' => 'high',
            ]))
            ->withApnsConfig(ApnsConfig::fromArray([
                'headers' => ['apns-priority' => '10'],
            ]));

        /** @var MulticastSendReport $report */
        $report = $messaging->sendMulticast($messagePayload, $tokens);

        // Handle invalid tokens
        foreach ($report->failures()->getItems() as $failure) {
            if ($failure->error()) {
                $error = $failure->error()->getMessage();

                if (
                    str_contains($error, 'registration-token-not-registered') ||
                    str_contains($error, 'Requested entity was not found') ||
                    str_contains($error, 'NotRegistered')
                ) {
                    UserFcmToken::where('fcm_token', $failure->target()->value())
                        ->update(['is_active' => false]);
                }
            }
        }

        return [
            'success' => $report->successes()->count(),
            'failure' => $report->failures()->count(),
        ];
    }


    /**
     * Test notification
     */
    public function sendTestNotification()
    {
        $fcmToken = "fXWZIfNTTAi-LBSgxpTxRp:APA91bF759UVT-N8-gsTbuMlvtAQjavdg32yb0kYa3rpd-VjkyBclEQoIzGHA0_gFx_H2rF5ljec4WxGO2dUS6qlCy_xNRlxxtO8joB31annBvaANYmnI7U"; // Replace with a real token
        $title = "Test Notification";
        $message = "This is a test notification from Laravel";
        // $sent = $this->sendToToken($fcmToken, $title, $message);

        // send to multiple
        $tokens = UserFcmToken::active()
            ->pluck('fcm_token')
            ->toArray();
        // dd($tokens);

        $result = $this->sendToMultipleTokens(
            $tokens,
            'New notification',
            'You have received a new notification'
        );
        // dd($result);

        //send to multiple
        return $result;
    }



    public function index()
    {
        $schedules = NotificationSchedule::orderBy('created_at', 'desc')->get();

        return view('admin.notifications.index', compact('schedules'));
    }
    public function createSchedule()
    {
        return view('admin.notifications.schedule');
    }
    public function editSchedule(NotificationSchedule $schedule)
    {
        return view('admin.notifications.schedule-form', compact('schedule'));
    }

    public function storeSchedule(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'language'       => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image_url' => 'nullable|url',
            'frequency'      => 'required|in:daily,weekly,monthly,yearly',
            'send_hour'      => 'required|integer|min:0|max:23',
            'send_minute'    => 'required|integer|min:0|max:59',
            'day_of_week'    => 'nullable|integer|min:0|max:6',
            'day_of_month'   => 'nullable|integer|min:1|max:31',
            'month_of_year'  => 'nullable|integer|min:1|max:12',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        NotificationSchedule::create([
            'language'       => $request->language,
            'title'          => $request->title,
            'message'        => $request->message,
            'image_url'      => $request->image_url,
            'frequency'      => $request->frequency,
            'send_hour'      => $request->send_hour,
            'send_minute'    => $request->send_minute,
            'day_of_week'    => $request->day_of_week,
            'day_of_month'   => $request->day_of_month,
            'month_of_year'  => $request->month_of_year,
            'is_active'      => $request->boolean('is_active'),
            'last_run_at'    => null,
        ]);

        return redirect()->back()->with('success', 'Notification schedule saved successfully.');
    }

    public function updateSchedule(Request $request, NotificationSchedule $schedule)
    {
        $validated = $request->validate([
            'language' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image_url' => 'nullable|url',
            'frequency' => 'required|in:daily,weekly,monthly,yearly,custom',
            'send_hour' => 'required|integer|min:0|max:23',
            'send_minute' => 'required|integer|min:0|max:59',
            'day_of_week' => 'nullable|integer|min:0|max:6',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'month_of_year' => 'nullable|integer|min:1|max:12',
            'is_active' => 'nullable|boolean',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Normalize fields based on frequency
    |--------------------------------------------------------------------------
    | This prevents stale values from previous frequencies
    */

        switch ($validated['frequency']) {
            case 'daily':
                $validated['day_of_week'] = null;
                $validated['day_of_month'] = null;
                $validated['month_of_year'] = null;
                break;

            case 'weekly':
                $validated['day_of_month'] = null;
                $validated['month_of_year'] = null;
                break;

            case 'monthly':
                $validated['day_of_week'] = null;
                $validated['month_of_year'] = null;
                break;

            case 'yearly':
                $validated['day_of_week'] = null;
                break;

            case 'custom':
                // keep all fields as provided
                break;
        }

        // Normalize checkbox
        $validated['is_active'] = $request->boolean('is_active');

        //  Update schedule
        $schedule->update($validated);

        return redirect()
            ->route('admin.notifications.schedule.edit', $schedule->id)
            ->with('success', 'Notification schedule updated successfully.');
    }

    public function destroy(NotificationSchedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.notifications.schedule.index')
            ->with('success', 'Notification schedule deleted successfully.');
    }

    public function sendInstant(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'image_url' => 'nullable|url',
        ]);

        $imageUrl = !empty($validated['image_url']) ? $validated['image_url'] : null;

        $this->sendToAll(
            $validated['title'],
            $validated['message'],
            $imageUrl,
            [
                'type' => 'instant',
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Instant notification sent successfully.');
    }
}
