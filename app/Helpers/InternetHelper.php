<?php

namespace App\Helpers;

class InternetHelper
{
    public static function isConnectedToInternet()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        }
        return false;
    }
}
