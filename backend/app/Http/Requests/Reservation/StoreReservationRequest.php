<?php

namespace App\Http\Requests\Reservation;

use App\Models\Companion;
use App\Validation\CompanionValidator;
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
     * Basic validation rules for creating a reservation.
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

    /**
     * Add custom companion validation after default rule validation.
     *
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        // Validations after rules
        $validator->after(function (Validator $validator) {
            // companions setup
            $companions = $this->input('companions');
            if (!is_array($companions)) {
                return;
            }
            // guest id setup
            $guestId = $this->input('guest_id');
            if (!$guestId) {
                return;
            }

            // Custom logic to check consistency between guest and companions
            CompanionValidator::validate($companions, $guestId, $validator);
        });
    }
}
