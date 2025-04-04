<?php

namespace App\Validation;

use Illuminate\Support\Facades\Validator;

/**
 * Class DocumentValidator
 *
 * Performs format validation for personal document numbers based on type.
 */
class DocumentValidator
{
    // TODO: include oficial document validation in the future

    /**
     * Validates the document number based on the given type (DNI, NIE, Passport).
     *
     * @param string $type The document type (e.g., 'DNI', 'NIE', 'Passport').
     * @param string $document The document number to validate.
     * @return string|null Returns the first validation error message, or null if valid.
     */
    public static function validate(string $type, string $document): ?string
    {

        switch ($type) {
            case 'DNI':
                $rules = ['required', 'regex:' . RegexRules::dni()];
                break;

            case 'NIE':
                $rules = ['required', 'regex:' . RegexRules::nie()];
                break;

            case 'Passport':
                $rules = ['required', 'regex:' . RegexRules::passport()];
                break;

            default:
                $rules = ['nullable'];
                break;
        }

        $validator = Validator::make(
            ['document' => $document],
            ['document' => $rules]
        );

        return $validator->fails()
            ? $validator->errors()->first('document')
            : null;
    }
}
