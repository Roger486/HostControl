<?php

namespace App\Http\Requests;

use App\Models\Companion;
use App\Validation\DocumentValidator;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
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
            'booked_by_id' => ['required', 'exists:users,id'],
            'guest_id' => ['required', 'exists:users,id'],
            'accommodation_id' => ['required', 'exists:accommodations,id'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'status' => ['prohibited'],
            'comments' => ['nullable', 'string', 'max:255'],

            // Companions validation rules
            'companions' => ['sometimes', 'array'],
            'companions.*.document_type' => ['sometimes', 'required', Rule::in(Companion::DOCUMENT_TYPES)],
            'companions.*.document_number' => ['sometimes', 'required','string', 'max:20'],
            // TODO: avoid repeated document_number on same reservation or like guest_id
            'companions.*.first_name' => ['required', 'string', 'max:100'],
            'companions.*.last_name_1' => ['required', 'string', 'max:100'],
            'companions.*.last_name_2' => ['nullable', 'string', 'max:100'],
            'companions.*.birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today']
        ];
    }

    public function withValidator(Validator $validator)
    {
        // Validations after rules
        $validator->after(function (Validator $validator) {
            $companions = $this->input('companions');

            if (!is_array($companions)) {
                return;
            }

            // Iterate through companions
            foreach ($companions as $index => $companion) {
                $birthdate = $companion['birthdate'] ?? null;
                $age = Carbon::parse($birthdate)->age;

                // If age is greater that 18
                if ($age >= 18) {
                    $type = $companion['document_type'] ?? null;
                    $documentNumber = $companion['document_number'] ?? null;

                    //document type and number are mandatory
                    if (!$type || !$documentNumber) {
                        $validator->errors()->add(
                            "companions.$index.document_number",
                            __('validation.custom.companions.*.document_number.required_for_adults')
                        );
                        continue;
                    }

                    // And should comply with validation
                    if ($errorMessage = DocumentValidator::validate($type, $documentNumber)) {
                        $validator->errors()->add("companions.$index.document_number", $errorMessage);
                    }
                }
            }
        });
    }
}
