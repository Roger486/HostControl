<?php

namespace App\Validation;

use Illuminate\Support\Facades\Validator;

class DocumentValidator
{
    // TODO: include oficial document validation in the future
    public static function validate(string $type, string $document): array
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

        return $validator->fails() ?
            $validator->errors()->get('document')
            : [];
    }
}
