<?php

namespace App\Services;

/**
 * Get hijri date from gregorian
 *
 * @author   Faiz Shukri
 * @date     5 Dec 2013
 * @url      https://gist.github.com/faizshukri/7802735
 *
 * Copyright 2013 | Faiz Shukri
 * Released under the MIT license
 */
class HijriDateService
{
    private $hijri;

    public function __construct($time = false)
    {
        if (!$time) {
            $time = time();
        }
        $this->hijri = $this->GregorianToHijri($time);
    }

    public function get_date()
    {
        return $this->hijri[1] . ' ' . $this->get_month_name($this->hijri[0]) . ' ' . $this->hijri[2] . ' AH';
    }

    public function get_day()
    {
        return $this->hijri[1];
    }

    public function get_month()
    {
        return $this->hijri[0];
    }

    public function get_year()
    {
        return $this->hijri[2];
    }

    public function get_month_name($i)
    {
        static $month = array(
            "Muharram", "Safar", "Rabi'ul Awwal", "Rabi'ul Akhir",
            "Jumadal Ula", "Jumadal Akhira", "Rajab", "Sha'ban",
            "Ramadan", "Shawwal", "Dhul Qa'ada", "Dhul Hijja"
        );
        return $month[$i - 1];
    }

    private function GregorianToHijri($time = null)
    {
        if ($time === null) {
            $time = time();
        }
        $m = date('m', $time);
        $d = date('d', $time);
        $y = date('Y', $time);

        return $this->JDToHijri(cal_to_jd(CAL_GREGORIAN, $m, $d, $y));
    }

    private function HijriToGregorian($m, $d, $y)
    {
        // This method is not critical for basic hijri date conversion
        // The main functionality is converting Gregorian to Hijri
        // For now, we'll just return null or a default value
        return null;
    }

    # Julian Day Count To Hijri
    private function JDToHijri($jd)
    {
        $jd = $jd - 1948440 + 10632;
        $n = (int)(($jd - 1) / 10631);
        $jd = $jd - 10631 * $n + 354;
        $j = ((int)((10985 - $jd) / 5316)) *
            ((int)(50 * $jd / 17719)) +
            ((int)($jd / 5670)) *
            ((int)(43 * $jd / 15238));
        $jd = $jd - ((int)((30 - $j) / 15)) *
            ((int)((17719 * $j) / 50)) -
            ((int)($j / 16)) *
            ((int)((15238 * $j) / 43)) + 29;
        $m = (int)(24 * $jd / 709);
        $d = $jd - (int)(709 * $m / 24);
        $y = 30 * $n + $j - 30;

        return array($m, $d, $y);
    }

    # Hijri To Julian Day Count
    private function HijriToJD($m, $d, $y)
    {
        return (int)((11 * $y + 3) / 30) +
            354 * $y + 30 * $m -
            (int)(($m - 1) / 2) + $d + 1948440 - 385;
    }
    
    /**
     * Alternative method to convert Hijri to Gregorian (simplified)
     */
    private function convertHijriToGregorian($m, $d, $y)
    {
        // Simplified conversion - this is an approximation
        // For accurate conversion, proper algorithms or libraries should be used
        $jd = $this->HijriToJD($m, $d, $y);
        $gregorian = $this->julianDayToGregorian($jd);
        return $gregorian;
    }
    
    /**
     * Convert Julian Day to Gregorian date
     */
    private function julianDayToGregorian($jd)
    {
        $jd = $jd + 0.5;
        $z = (int)$jd;
        $f = $jd - $z;
        
        $a = $z;
        if ($z >= 2299161) {
            $alpha = (int)((($z - 1867216) - 0.25) / 36524.25);
            $a = $z + 1 + $alpha - (int)(0.25 * $alpha);
        }
        
        $b = $a + 1524;
        $c = (int)(((int)(($b - 122.1) / 365.25)) * 365.25);
        $d = (int)(($b - $c) / 30.6001);
        
        $day = $b - $c - (int)($d * 30.6001);
        $month = $d < 14 ? $d - 1 : $d - 13;
        $year = $month > 2 ? ((int)(($a - 1867216.25) / 36524.25)) + 4716 : ((int)(($a - 1867216.25) / 36524.25)) + 4715;
        
        return array($month, $day, $year);
    }
}
