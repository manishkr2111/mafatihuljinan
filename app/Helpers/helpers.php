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
            'sahifas-ahlulbayt'     => \App\Models\English\EnglishSahifasAhlulbayt::class,
            'surah'                 => \App\Models\English\Surah::class,
            'dua'                   => \App\Models\English\Dua::class,
            'daily-dua'             => \App\Models\English\DailyDua::class,
            'amaal-namaz'           => \App\Models\English\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\English\BurialActsPrayers::class,
            'amaal'                 => \App\Models\English\Amaal::class,
            'munajat'               => \App\Models\English\Munajat::class,
            'salaat-namaz'          => \App\Models\English\SalaatNamaz::class,
            'salwaat'               => \App\Models\English\SalaatNamaz::class,
            'tasbih'                => \App\Models\English\Tasbih::class,
            'travel-ziyarat'        => \App\Models\English\TravelZiyarat::class,
            'ziyarat'               => \App\Models\English\Ziyarat::class,
            'essential-supplications' => \App\Models\English\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
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
            'essential-supplications' => 'Essential Supplications',
            'amaal-namaz'=>'Amaal Namaz',
            'burial-acts-prayers'=> 'Burial Acts Prayers',
            'munajat'=>'Munajat',
            'salaat-namaz'=>'Salaat Namaz',
            'salwaat'=>'Salwaat',
            'tasbih'=>'Tasbih',

        ];
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
