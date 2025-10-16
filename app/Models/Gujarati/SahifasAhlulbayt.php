<?php

namespace App\Models\Gujarati;

use Illuminate\Database\Eloquent\Model;

class SahifasAhlulbayt extends Model
{
    protected $table = 'gujarati_sahifas_ahlulbayt';

    protected $fillable = [
        'title',
        'search_text',
        'redirect_deep_link',
        'roman_data',
        'sort_number',
        'arabic_islrc',
        'arabic_4line',
        'arabic_audio_url',
        'arabic_content',
        'simple_arabic',
        'transliteration_islrc',
        'transliteration_4line',
        'transliteration_audio_url',
        'transliteration_content',
        'simple_transliteration',
        'translation_islrc',
        'translation_4line',
        'translation_audio_url',
        'translation_content',
        'simple_translation',
        'next_post_title',
        'next_post_url',
        'internal_link',
        'category_ids',
        'status',
    ];

    protected $casts = [
        'category_ids' => 'array', // automatically casts JSON to array
    ];

    // Helper to get assigned categories
    public function old_categories()
    {
        return \App\Models\Gujarati\Category::whereIn('id', $this->category_ids ?? [])->get();
    }
    public function categories()
    {
        // Ensure category_ids is always an array
        $ids = $this->category_ids;

        if (is_string($ids)) {
            $ids = json_decode($ids, true) ?? [];
        }

        if (!is_array($ids)) {
            $ids = [];
        }

        return \App\Models\Gujarati\Category::whereIn('id', $ids)->get();
    }
}


