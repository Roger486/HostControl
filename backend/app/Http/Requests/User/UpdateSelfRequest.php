<?php

namespace App\Http\Requests\User;

use App\Validation\RegexRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSelfRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name_1' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name_2' => ['sometimes', 'nullable', 'string', 'max:100'],
            'password' => ['sometimes', 'required', 'string', 'min:4', 'max:100'],
            'birthdate' => ['sometimes', 'required', 'date_format:Y-m-d', 'date', 'before:today'],
            'address' => ['sometimes', 'required', 'string', 'min:10', 'max:255'],
            'phone' => ['sometimes', 'required', 'regex:' . RegexRules::phone()],
            'comments' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }
}
