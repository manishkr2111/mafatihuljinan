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

if (!function_exists('getHindiModel')) {
    function getHindiModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\Hindi\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\Hindi\Surah::class,
            'dua'                   => \App\Models\Hindi\Dua::class,
            'daily-dua'             => \App\Models\Hindi\DailyDua::class,
            'amaal-namaz'           => \App\Models\Hindi\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\Hindi\BurialActsPrayers::class,
            'amaal'                 => \App\Models\Hindi\Amaal::class,
            'munajat'               => \App\Models\Hindi\Munajat::class,
            'salaat-namaz'          => \App\Models\Hindi\SalaatNamaz::class,
            'salwaat'               => \App\Models\Hindi\Salwaat::class,
            'tasbih'                => \App\Models\Hindi\Tasbih::class,
            'travel-ziyarat'        => \App\Models\Hindi\TravelZiyarat::class,
            'ziyarat'               => \App\Models\Hindi\Ziyarat::class,
            'essential-supplications' => \App\Models\Hindi\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}

if (!function_exists('getUrduModel')) {
    function getUrduModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\Urdu\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\Urdu\Surah::class,
            'dua'                   => \App\Models\Urdu\Dua::class,
            'daily-dua'             => \App\Models\Urdu\DailyDua::class,
            'amaal-namaz'           => \App\Models\Urdu\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\Urdu\BurialActsPrayers::class,
            'amaal'                 => \App\Models\Urdu\Amaal::class,
            'munajat'               => \App\Models\Urdu\Munajat::class,
            'salaat-namaz'          => \App\Models\Urdu\SalaatNamaz::class,
            'salwaat'               => \App\Models\Urdu\Salwaat::class,
            'tasbih'                => \App\Models\Urdu\Tasbih::class,
            'travel-ziyarat'        => \App\Models\Urdu\TravelZiyarat::class,
            'ziyarat'               => \App\Models\Urdu\Ziyarat::class,
            'essential-supplications' => \App\Models\Urdu\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}
if (!function_exists('getFrenchModel')) {
    function getFrenchModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\French\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\French\Surah::class,
            'dua'                   => \App\Models\French\Dua::class,
            'daily-dua'             => \App\Models\French\DailyDua::class,
            'amaal-namaz'           => \App\Models\French\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\French\BurialActsPrayers::class,
            'amaal'                 => \App\Models\French\Amaal::class,
            'munajat'               => \App\Models\French\Munajat::class,
            'salaat-namaz'          => \App\Models\French\SalaatNamaz::class,
            'salwaat'               => \App\Models\French\Salwaat::class,
            'tasbih'                => \App\Models\French\Tasbih::class,
            'travel-ziyarat'        => \App\Models\French\TravelZiyarat::class,
            'ziyarat'               => \App\Models\French\Ziyarat::class,
            'essential-supplications' => \App\Models\French\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}

if (!function_exists('getSwahiliModel')) {
    function getSwahiliModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\Urdu\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\Urdu\Surah::class,
            'dua'                   => \App\Models\Urdu\Dua::class,
            'daily-dua'             => \App\Models\Urdu\DailyDua::class,
            'amaal-namaz'           => \App\Models\Urdu\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\Urdu\BurialActsPrayers::class,
            'amaal'                 => \App\Models\Urdu\Amaal::class,
            'munajat'               => \App\Models\Urdu\Munajat::class,
            'salaat-namaz'          => \App\Models\Urdu\SalaatNamaz::class,
            'salwaat'               => \App\Models\Urdu\Salwaat::class,
            'tasbih'                => \App\Models\Urdu\Tasbih::class,
            'travel-ziyarat'        => \App\Models\Urdu\TravelZiyarat::class,
            'ziyarat'               => \App\Models\Urdu\Ziyarat::class,
            'essential-supplications' => \App\Models\Urdu\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}

if (!function_exists('getRomanUrduModel')) {
    function getRomanUrduModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\RomanUrdu\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\RomanUrdu\Surah::class,
            'dua'                   => \App\Models\RomanUrdu\Dua::class,
            'daily-dua'             => \App\Models\RomanUrdu\DailyDua::class,
            'amaal-namaz'           => \App\Models\RomanUrdu\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\RomanUrdu\BurialActsPrayers::class,
            'amaal'                 => \App\Models\RomanUrdu\Amaal::class,
            'munajat'               => \App\Models\RomanUrdu\Munajat::class,
            'salaat-namaz'          => \App\Models\RomanUrdu\SalaatNamaz::class,
            'salwaat'               => \App\Models\RomanUrdu\Salwaat::class,
            'tasbih'                => \App\Models\RomanUrdu\Tasbih::class,
            'travel-ziyarat'        => \App\Models\RomanUrdu\TravelZiyarat::class,
            'ziyarat'               => \App\Models\RomanUrdu\Ziyarat::class,
            'essential-supplications' => \App\Models\RomanUrdu\EssentialSupplications::class,
        ];

        return $map[$postType] ?? null;
    }
}

if (!function_exists('getFrenchModel')) {
    function getUrduModel($postType)
    {
        $map = [
            'sahifas-ahlulbayt'     => \App\Models\Urdu\SahifasAhlulbayt::class,
            'surah'                 => \App\Models\Urdu\Surah::class,
            'dua'                   => \App\Models\Urdu\Dua::class,
            'daily-dua'             => \App\Models\Urdu\DailyDua::class,
            'amaal-namaz'           => \App\Models\Urdu\AmaalNamaz::class,
            'burial-acts-prayers'   => \App\Models\Urdu\BurialActsPrayers::class,
            'amaal'                 => \App\Models\Urdu\Amaal::class,
            'munajat'               => \App\Models\Urdu\Munajat::class,
            'salaat-namaz'          => \App\Models\Urdu\SalaatNamaz::class,
            'salwaat'               => \App\Models\Urdu\Salwaat::class,
            'tasbih'                => \App\Models\Urdu\Tasbih::class,
            'travel-ziyarat'        => \App\Models\Urdu\TravelZiyarat::class,
            'ziyarat'               => \App\Models\Urdu\Ziyarat::class,
            'essential-supplications' => \App\Models\Urdu\EssentialSupplications::class,
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

if (!function_exists('FrenchPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function FrenchPostTypeOptions()
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

if (!function_exists('UrduPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function UrduPostTypeOptions()
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
if (!function_exists('RomanUrduPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function RomanUrduPostTypeOptions()
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

if (!function_exists('SwahiliPostTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function SwahiliPostTypeOptions()
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
if (!function_exists('validLanguages')) {
    function validLanguages(): array
    {
        return [
            'english',
            'gujarati',
            'hindi',
            'french',
            'urdu',
            'roman urdu',
            'swahili',
        ];
    }
}


if (!function_exists('isfavoritePosts')) {
    function isFavoritePosts($post_type, $posts, $user, $language = 'English', $parent_category_id = null)
    {
        $user_id = $user->id;
        // Get all favorites for this user, post type, and language
        $user_fav_post_ids = \App\Models\Common\Favorite::where('user_id', $user_id)
            ->where('post_type', $post_type)
            ->where('language', $language)
            ->pluck('post_id')
            ->toArray();

        // Map posts to include is_fav
        $posts->map(function ($post) use ($user_fav_post_ids, $post_type, $parent_category_id) {
            $post->post_type = $post_type;
            $post->parent_category_id = $parent_category_id;
            $post->is_fav = in_array($post->id, $user_fav_post_ids);
            return $post;
        });
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
            case 'hindi':
                return getHindiModel($postType);
            case 'urdu':
                return getUrduModel($postType);
            case 'french':
                return getFrenchModel($postType);
            case 'swahili':
                return getSwahiliModel($postType);
            case 'roman urdu':
                return getRomanUrduModel($postType);
            default:
                return null;
        }
    }
}
