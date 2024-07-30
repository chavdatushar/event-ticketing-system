<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class UrlHelper
{
    public static function encrypt($value)
    {
        return Crypt::encryptString($value);
    }

    public static function decrypt($value)
    {
        return Crypt::decryptString($value);
    }
}
