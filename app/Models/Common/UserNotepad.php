<?php

namespace App\Models\Common;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotepad extends Model
{
     use HasFactory;

    protected $table = 'user_notepad';

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'language',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
