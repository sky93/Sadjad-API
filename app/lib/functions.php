<?php

namespace App\lib;


class functions
{

    static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * Converts bytes to B, KB , MB, ..., YB
     *
     * @param $bytes
     * @param int $precision
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    static function formatBytes($bytes, $precision = 2, $dec_point = '.', $thousands_sep = ',')
    {
        $negative = $bytes < 0;
        if ($negative) $bytes *= -1;
        $size = $bytes;
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        $sz = $size / pow(1024, $power);
        if ($sz - round($sz) == 0) $precision = 0;
        if ($negative) $sz *= -1;
        return number_format($sz, $precision, $dec_point, $thousands_sep) . ' ' . $units[$power];
    }


    static function number_fix($number)
    {
        // If user's input has Arabic/Persian numbers, we change it to standard english numbers
        $persian_numbers = [
            '۰' => '0', '٠' => '0',
            '۱' => '1', '١' => '1',
            '۲' => '2', '٢' => '2',
            '۳' => '3', '٣' => '3',
            '۴' => '4', '٤' => '4',
            '۵' => '5', '٥' => '5',
            '۶' => '6', '٦' => '6',
            '۷' => '7', '٧' => '7',
            '۸' => '8', '٨' => '8',
            '۹' => '9', '٩' => '9',
        ];

        return strtr($number, $persian_numbers);
    }


    static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

}
