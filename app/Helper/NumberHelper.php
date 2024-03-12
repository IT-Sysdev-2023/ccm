<?php

namespace App\Helper;

use NumberFormatter;

// use PhpOffice\PhpSpreadsheet\Style\NumberFormat\NumberFormatter;

class NumberHelper
{
    public static function float($number)
    {
        return (float) str_replace(',', '', $number);
    }

    public static function format($number)
    {
        return number_format($number, 2);
    }
    public static function currency(float $amount, $locale = 'en_PH', string $currency = 'PHP')
    {
        $numberFormatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $numberFormatter->formatCurrency($amount, $currency);
    }

    public static function percentage($current, $total){
        $percent = ($current / $total) * 100;
        return self::format($percent);
    } 
}