<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TafsirData extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'post_type',
        'content',
        'language',
        'tafsir_html_content'
    ];
}
