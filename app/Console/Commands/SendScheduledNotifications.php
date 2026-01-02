<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Common\NotificationSchedule;
use App\Http\Controllers\Admin\Common\NotificationController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send-scheduled';
    protected $description = 'Send scheduled notifications';

    public function handle(): int
    {
        // $now = Carbon::now();
        // $now = Carbon::now('Asia/Kolkata');
        $now = Carbon::now('UTC');
        // dd('Command is running', $now);

        $schedules = NotificationSchedule::active()->get();

        foreach ($schedules as $schedule) {

            if (! $this->isDue($schedule, $now)) {
                continue;
            }

            try {
                //  Send notification
                app(NotificationController::class)->sendToAll(
                    $schedule->title,
                    $schedule->message,
                    !empty($schedule->image_url) ? $schedule->image_url : null,
                    [
                        'schedule_id' => $schedule->id,
                        'frequency' => $schedule->frequency,
                    ]
                );


                // ⏱ Update last run
                $schedule->update([
                    'last_run_at' => $now,
                ]);
            } catch (\Throwable $e) {
                Log::error('Scheduled notification failed', [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Determine if the schedule should fire now
     */
    protected function isDue(NotificationSchedule $schedule, Carbon $now): bool
    {
        // Time match
        if (
            (int) $schedule->send_hour !== $now->hour ||
            (int) $schedule->send_minute !== $now->minute
        ) {
            return false;
        }

        // Prevent duplicate run in same minute
        if (
            $schedule->last_run_at &&
            $schedule->last_run_at->isSameMinute($now)
        ) {
            return false;
        }

        return match ($schedule->frequency) {
            'daily'   => true,

            'weekly'  => (int) $schedule->day_of_week === $now->dayOfWeek,

            'monthly' => (int) $schedule->day_of_month === $now->day,

            'yearly'  =>
            (int) $schedule->day_of_month === $now->day &&
                (int) $schedule->month_of_year === $now->month,

            'custom'  => $this->customMatch($schedule, $now),

            default   => false,
        };
    }

    protected function customMatch(NotificationSchedule $schedule, Carbon $now): bool
    {
        if (
            $schedule->day_of_week !== null &&
            (int) $schedule->day_of_week !== $now->dayOfWeek
        ) {
            return false;
        }

        if (
            $schedule->day_of_month !== null &&
            (int) $schedule->day_of_month !== $now->day
        ) {
            return false;
        }

        if (
            $schedule->month_of_year !== null &&
            (int) $schedule->month_of_year !== $now->month
        ) {
            return false;
        }

        return true;
    }
}
