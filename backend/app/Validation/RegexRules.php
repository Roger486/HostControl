<?php

namespace App\Validation;

/**
 * Class RegexRules
 *
 * Provides reusable regex expressions for form validation.
 */
class RegexRules
{
    /**
     * @var string Phone number format: optional '+' followed by digits, spaces, or hyphens (7–20 chars)
     */
    private static $phone = '/^\+?[0-9\s\-]{7,20}$/';
    /**
     * @var string DNI format: 8 digits followed by an uppercase letter from official list
     */
    private static $dni = '/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]$/';
    /**
     * @var string NIE format: starts with X/Y/Z, followed by 7 digits and a control letter
     */
    private static $nie = '/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]$/';
    /**
     * @var string Passport format: 5 to 20 alphanumeric uppercase characters
     */
    private static $passport = '/^[A-Za-z0-9]{5,20}$/';

    /**
     * Get the regex for validating phone numbers.
     *
     * @return string
     */
    public static function phone(): string
    {
        return self::$phone;
    }

    /**
     * Get the regex for validating DNI numbers.
     *
     * @return string
     */
    public static function dni(): string
    {
        return self::$dni;
    }

    /**
     * Get the regex for validating NIE numbers.
     *
     * @return string
     */
    public static function nie(): string
    {
        return self::$nie;
    }

    /**
     * Get the regex for validating passport numbers.
     *
     * @return string
     */
    public static function passport(): string
    {
        return self::$passport;
    }
}
