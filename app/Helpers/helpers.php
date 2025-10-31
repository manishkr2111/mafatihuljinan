<?php

use App\Models\Menu;

if (!function_exists('lastDataUpdatedTime')) {

    function lastDataUpdatedTime($postType, $language = 'english')
    {
        $menus = Menu::where('post_type', $postType)->where('language', $language)->get();
        foreach ($menus as $menu) {
            $menu->last_data_updated_at = \Carbon\Carbon::now();
            $menu->save();
        }
    }
}

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
            'salwaat'               => \App\Models\English\Salwaat::class,
            'tasbih'                => \App\Models\English\Tasbih::class,
            'travel-ziyarat'        => \App\Models\English\TravelZiyarat::class,
            'ziyarat'               => \App\Models\English\Ziyarat::class,
            'essential-supplications' => \App\Models\English\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}

if (!function_exists('commonPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function commonPostTypeOptions()
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
            'amaal-namaz' => 'Amaal Namaz',
            'burial-acts-prayers' => 'Burial Acts Prayers',
            'munajat' => 'Munajat',
            'salaat-namaz' => 'Salaat Namaz',
            'salwaat' => 'Salwaat',
            'tasbih' => 'Tasbih',

        ];
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
            'amaal-namaz' => 'Amaal Namaz',
            'burial-acts-prayers' => 'Burial Acts Prayers',
            'munajat' => 'Munajat',
            'salaat-namaz' => 'Salaat Namaz',
            'salwaat' => 'Salwaat',
            'tasbih' => 'Tasbih',

        ];
    }
}

if (!function_exists('isfavoritePost')) {
    function isFavoritePosts($language = 'English', $post_type, $posts)
    {
        $user_id = auth()->id();

        // Get all favorites for this user, post type, and language
        $user_fav_post_ids = \App\Models\Common\Favorite::where('user_id', $user_id)
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


if (!function_exists('getGujaratiModel')) {
    function getGujaratiModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\Gujarati\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\Gujarati\Surah::class,
            'dua'                   => \App\Models\Gujarati\Dua::class,
            'daily-dua'             => \App\Models\Gujarati\DailyDua::class,
            'amaal-namaz'           => \App\Models\Gujarati\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\Gujarati\BurialActsPrayers::class,
            'amaal'                 => \App\Models\Gujarati\Amaal::class,
            'munajat'               => \App\Models\Gujarati\Munajat::class,
            'salaat-namaz'          => \App\Models\Gujarati\SalaatNamaz::class,
            'salwaat'               => \App\Models\Gujarati\Salwaat::class,
            'tasbih'                => \App\Models\Gujarati\Tasbih::class,
            'travel-ziyarat'        => \App\Models\Gujarati\TravelZiyarat::class,
            'ziyarat'               => \App\Models\Gujarati\Ziyarat::class,
            'essential-supplications' => \App\Models\Gujarati\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
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
            'ziyarat' => 'Ziyarat',
            'essential-supplications' => 'Essential Supplications',
            'amaal-namaz' => 'Amaal Namaz',
            'burial-acts-prayers' => 'Burial Acts Prayers',
            'munajat' => 'Munajat',
            'salaat-namaz' => 'Salaat Namaz',
            'salwaat' => 'Salwaat',
            'tasbih' => 'Tasbih',
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


if (!function_exists('validLanguages')) {
    function validLanguages(): array
    {
        return [
            'english',
            'gujarati',
            'hindi',
            'french',
        ];
    }
}

if (!function_exists('getModelByLanguageAndType')) {
    function getModelByLanguageAndType($language, $postType)
    {
        switch (strtolower($language)) {
            case 'gujarati':
                return getGujaratiModel($postType);
            case 'english':
                return getEnglishModel($postType);
            // case 'arabic':
            //     return getArabicModel($postType);
            default:
                return null;
        }
    }
}

