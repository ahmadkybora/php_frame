<?php
namespace App\Services;

class HelperFunction {
    public static function dd($value)
    {
        echo '<pre style="background-color:orange; width: 400px; font-size: 30px;">';
        var_dump($value);
        echo '</pre>';
        exit;
    }
}