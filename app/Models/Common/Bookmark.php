<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'post_type',
        'language',
        'bookmark_indexes',
        'indexes_arabic',
        'indexes_transliteration',
        'indexes_translation',
    ];

    protected $casts = [
        'indexes_arabic'          => 'array',
        'indexes_transliteration' => 'array',
        'indexes_translation'     => 'array',
    ];
}
