<?php

if (!function_exists('greet_user')) {
    function greet_user($name)
    {
        return "Hello, " . $name . "!";
    }
}

if (!function_exists('getEnglishModel')) {
    function getEnglishModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt' => \App\Models\EnglishSahifasAhlulbayt::class,
            'surah' => \App\Models\EnglishSahifasAhlulbayt::class,
            'dua' => \App\Models\EnglishSahifasAhlulbayt::class,

        ];

        return $map[$postType] ?? null;
    }
}
if (!function_exists('isfavoritePost')) {
    function isFavoritePosts($language = 'English', $post_type, $posts)
    {
        $user_id = auth()->id();

        // Get all favorites for this user, post type, and language
        $user_fav_post_ids = \App\Models\Favorite::where('user_id', $user_id)
            ->where('post_type', $post_type)
            ->where('language', $language)
            ->pluck('post_id')
            ->toArray();

        // Map posts to include is_fav
        $posts->map(function ($post) use ($user_fav_post_ids) {
            $post->is_fav = in_array($post->id, $user_fav_post_ids);
            return $post;
        });
    }
}

if (!function_exists('EnglishPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function EnglishPostTypeOptions()
    {
        return [
            'sahifas-ahlulbayt' => 'Sahifas Ahlulbayt',
            'surah' => 'Surah',
            'daily-dua' => 'Daily Dua',
            'dua' => 'Dua',
            'amaal' => 'Amaal',
            'travel-ziyarat' => 'Travel Ziyarat',
            'ziyarat' => 'Ziyarat',
            // add more post types here in future
        ];
    }
}
if (!function_exists('GujaratiPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function GujaratiPostTypeOptions()
    {
        return [
            'sahifas-ahlulbayt' => 'Sahifas Ahlulbayt',
            'surah' => 'Surah',
            'daily-dua' => 'Daily Dua',
            'dua' => 'Dua',
            'amaal' => 'Amaal',
            'travel-ziyarat' => 'Travel Ziyarat',
            'gujarati-ziyarat' => 'Ziyarat',
            // add more post types here in future
        ];
    }
}
if (!function_exists('HindiPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function HindiPostTypeOptions()
    {
        return [
            'hindi-sahifas-ahlulbayt' => 'Sahifas Ahlulbayt',
            'hindi-surah' => 'Surah',
            'hindi-daily-dua' => 'Daily Dua',
            'hindi-dua' => 'Dua',
            'hindi-amaal' => 'Amaal',
            'hindi-travel-ziyarat' => 'Travel Ziyarat',
            'hindi-ziyarat' => 'Ziyarat',
            // add more post types here in future
        ];
    }
}
