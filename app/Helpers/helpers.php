<?php

if (!function_exists('greet_user')) {
    function greet_user($name)
    {
        return "Hello, " . $name . "!";
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