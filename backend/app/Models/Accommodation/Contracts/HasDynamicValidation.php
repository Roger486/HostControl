<?php

namespace App\Models\Accommodation\Contracts;

/**
 * Contract for accommodation subtypes that define their own validation rules.
 *
 * Any model implementing this interface must provide a static `rules()` method
 * that returns an array of Laravel-compatible validation rules, depending on the operation type.
 */
interface HasDynamicValidation
{
    /**
     * Get the validation rules for the subtype.
     *
     * @param string $operation Context of the validation. Can be 'store' or 'update'. Defaults to 'store'.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(string $operation = 'store'): array;
}
