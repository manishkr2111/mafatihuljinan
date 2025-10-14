<?php

if (!function_exists('greet_user')) {
    function greet_user($name)
    {
        return "Hello, " . $name . "!";
    }
}

if (!function_exists('postTypeOptions')) {
    /**
     * Return all post type options as [value => label]
     *
     * @return array
     */
    function postTypeOptions()
    {
        return [
            'sahifas-shlulbayt' => 'Sahifas Shlulbayt',
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