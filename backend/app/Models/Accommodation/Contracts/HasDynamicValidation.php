<?php

namespace App\Models\Accommodation\Contracts;

/**
 * Contract for accommodation subtypes that define their own validation rules.
 * Any model that implements this interface is expected to provide a public static method `rules()`
 * which returns an array of validation rules compatible with Laravel's validator.
 */
interface HasDynamicValidation
{
    /**
     * Returns an array with the validation rules for the subtype.
     * @param string $operation The validation context: 'store' or 'update' (store if no parameter)
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(string $operation = 'store'): array;
}
