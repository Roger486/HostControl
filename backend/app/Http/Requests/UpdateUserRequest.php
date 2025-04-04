<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Validation\DocumentValidator;
use App\Validation\RegexRules;
use Carbon\Carbon;
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
        // Validations after rules
        $validator->after(function (Validator $validator) {
            // companions setup
            $companions = $this->input('companions');
            if (!is_array($companions)) {
                return;
            }

            // guest and seen document_numbers setup
            $guestId = $this->input('guest_id');
            $guest = User::find($guestId);
            $seenDocuments = [];
            if ($guest && $guest->document_number) {
                $seenDocuments[] = strtoupper(trim($guest->document_number));
            }

            // Iterate through companions
            foreach ($companions as $index => $companion) {
                $birthdate = $companion['birthdate'] ?? null;
                $age = Carbon::parse($birthdate)->age;

                $type = $companion['document_type'] ?? null;
                $documentNumber = $companion['document_number'] ?? null;

                if ($age < 18) {
                    // if companion is not adult and passed only one field (type or number)
                    if (($type && !$documentNumber) || (!$type && $documentNumber)) {
                        $validator->errors()->add(
                            "companions.$index.document_number",
                            __('validation.custom.companions.*.document_number.both_or_none_for_minors')
                        );
                    }
                }

                // if companion is adult, type and number are required
                if ($age >= 18 && (!$type || !$documentNumber)) {
                    $validator->errors()->add(
                        "companions.$index.document_number",
                        __('validation.custom.companions.*.document_number.required_for_adults')
                    );
                    continue;
                }

                // if type and number are present validate them
                if ($type && $documentNumber) {
                    if ($errorMessage = DocumentValidator::validate($type, $documentNumber)) {
                        $validator->errors()->add("companions.$index.document_number", $errorMessage);
                    }

                    $normalizedDoc = strtoupper(trim($documentNumber));
                    if (in_array($normalizedDoc, $seenDocuments)) {
                        $validator->errors()->add(
                            "companions.$index.document_number",
                            __('validation.custom.companions.*.document_number.not_repeated')
                        );
                    }

                    $seenDocuments[] = $normalizedDoc;
                }
            }
        });
    }
}
