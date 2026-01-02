<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserFcmToken extends Model
{
    use HasFactory;

    protected $table = 'user_fcm_tokens';

    protected $fillable = [
        'language',
        'user_id',
        'fcm_token',
        'device_type',
        'device_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: Token belongs to a user
     */
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    /**
     * Scope: Only active tokens
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
