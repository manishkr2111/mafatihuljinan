<?php

namespace App\Models\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSchedule extends Model
{
    use HasFactory;

    protected $table = 'notification_schedules';

    protected $fillable = [
        'language',
        'title',
        'message',
        'image_url',
        'frequency',
        'send_hour',
        'send_minute',
        'day_of_week',
        'day_of_month',
        'month_of_year',
        'last_run_at',
        'is_active',
    ];

    protected $casts = [
        'send_hour'     => 'integer',
        'send_minute'   => 'integer',
        'day_of_month'  => 'integer',
        'month_of_year' => 'integer',
        'last_run_at'   => 'datetime',
        'is_active'     => 'boolean',
    ];

    /**
     * Scope: Only active schedules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by frequency
     */
    public function scopeFrequency($query, string $frequency)
    {
        return $query->where('frequency', $frequency);
    }
}
