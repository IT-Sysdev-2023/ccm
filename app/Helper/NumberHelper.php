<?php

namespace App\Helper;

class NumberHelper{
    public static function float($number){
        return (float) str_replace(',', '', $number);
    }

    public function format($number){
        return number_format($number, 2);
    }
}