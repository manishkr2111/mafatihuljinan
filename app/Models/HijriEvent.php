<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HijriEvent extends Model
{
    use HasFactory;

    // Allow mass assignment on these fields
    protected $fillable = [
        'date',
        'month',
        'event',
        'language',
        'text_color',
    ];

    public static function getEventForDate($hijriDay, $hijriMonthName, $language = 'english')
    {
        return self::where('date', $hijriDay)
            ->where('month', $hijriMonthName)
            ->where('language', $language)
            ->first();
    }
}
