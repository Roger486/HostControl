<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Validation\DocumentValidator;
use App\Validation\RegexRules;
use Illuminate\Contracts\Validation\Validator;
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
            'document_type' => ['required_with:document_number', Rule::in(User::DOCUMENT_TYPES)],
            'document_number' => [
                'required_with:document_type', 'string', 'max:20',
                Rule::unique('users', 'document_number')->ignore($this->user->id)
            ],
            'phone' => ['sometimes', 'required', 'regex:' . RegexRules::phone()],
            // TODO: role management handled via dedicated admin route/controller
            //'role' => ['sometimes', 'nullable', Rule::in(User::ROLES)],
            'comments' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $type = $this->input('document_type');
            $documentNumber = $this->input('document_number');

            if (!$type || !$documentNumber) {
                return;
            }

            if ($errorMessage = DocumentValidator::validate($type, $documentNumber)) {
                $validator->errors()->add('document_number', $errorMessage);
            }
        });
    }
}
