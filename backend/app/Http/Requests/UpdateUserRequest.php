<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Validation\RegexRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => [
                'sometimes', 'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->user->id)
            ],
            'password' => ['sometimes', 'required', 'string', 'min:4', 'max:100'],
            'birthdate' => ['sometimes', 'required', 'date_format:Y-m-d', 'date', 'before:today'],
            'address' => ['sometimes', 'required', 'string', 'min:10', 'max:255'],
            // TODO: In withValidator() → if document_type changes, require document_number as well
            // TODO: Validate document_number format depending on document_type (DNI, NIE, PASSPORT)
            'document_type' => ['sometimes', 'required', Rule::in(User::DOCUMENT_TYPES)],
            'document_number' => [
                'sometimes', 'required', 'string', 'max:20',
                Rule::unique('users', 'document_number')->ignore($this->user->id)
            ],
            'phone' => ['sometimes', 'required', 'regex:' . RegexRules::phone()],
            // TODO: role management handled via dedicated admin route/controller
            //'role' => ['sometimes', 'nullable', Rule::in(User::ROLES)],
            'comments' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }
}
