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
        'bookmark_indexes',
        'post_type',
        'language',
    ];
}
