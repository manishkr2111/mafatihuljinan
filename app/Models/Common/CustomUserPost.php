<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomUserPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id',
        'content',
        'audio_url',
        'arabic_content',
        'transliteration_content',
        'translation_content',
        'language'
    ];
}
