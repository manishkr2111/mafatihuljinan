<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventPopup extends Model
{
    use HasFactory;

    protected $table = 'event_popups';

    protected $fillable = [
        'title',
        'imgurl',
        'date',
        'month',
        'language',
    ];
}
