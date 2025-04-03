<?php

namespace App\Validation;

class RegexRules
{
    private static $phone = '/^\+?[0-9\s\-]{7,20}$/';
    private static $dni = '/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]$/';
    private static $nie = '/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]$/';
    private static $passport = '/^[A-Z0-9]{5,20}$/';

    public static function phone(): string
    {
        return self::$phone;
    }

    public static function dni(): string
    {
        return self::$dni;
    }

    public static function nie(): string
    {
        return self::$nie;
    }

    public static function passport(): string
    {
        return self::$passport;
    }
}
