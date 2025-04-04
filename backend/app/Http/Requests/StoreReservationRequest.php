<?php

namespace App\Http\Requests;

use App\Models\Companion;
use App\Models\User;
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
