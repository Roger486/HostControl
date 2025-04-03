<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Validation\RegexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name_1' => ['required', 'string', 'max:100'],
            'last_name_2' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4', 'max:100'],
            'birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
            'document_type' => ['required', Rule::in(User::DOCUMENT_TYPES)],
            // TODO: document_number -> withValidator() -> depends on document_type
            'document_number' => ['required', 'string', 'max:20', 'unique:users,document_number'],
            'phone' => ['required', 'regex:' . RegexRules::phone()],
            // TODO: role management handled via dedicated admin route/controller
            //'role' => ['nullable', Rule::in(User::ROLES)],
            'comments' => ['nullable', 'string', 'max:255']
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number may start with +, must be 7 to 20 characters long and '
                . 'can include digits(0-9), spaces( ), or hyphens(-).',
        ];
    }
}
