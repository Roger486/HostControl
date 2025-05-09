<?php

namespace App\Http\Requests\Reservation;

use App\Models\Companion;
use App\Models\Reservation;
use App\Validation\CompanionValidator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a reservation.
     * Only the submitted fields will be validated.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // TODO: allow accommodation_id update and all the logic related to it (availability, date checks, etc.)
        return [
            'guest_id' => ['sometimes', 'required', 'exists:users,id'],
            'accommodation_id' => ['prohibited'],
            'check_in_date' => ['required_with:check_out_date', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required_with:check_in_date', 'date', 'after:check_in_date'],
            'status' => ['sometimes', Rule::in(Reservation::STATUSES)],
            'comments' => ['nullable', 'string', 'max:255'],

            // Log detail for the reservation log
            'log_detail' => ['required', 'string', 'min:4', 'max:255'],

            // Companions validation rules
            'companions' => ['sometimes', 'array'],
            'companions.*.document_type' => ['sometimes', 'required', Rule::in(Companion::DOCUMENT_TYPES)],
            'companions.*.document_number' => ['sometimes', 'required', 'string', 'max:20'],
            'companions.*.first_name' => ['required', 'string', 'max:100'],
            'companions.*.last_name_1' => ['required', 'string', 'max:100'],
            'companions.*.last_name_2' => ['nullable', 'string', 'max:100'],
            'companions.*.birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today']
        ];
    }

    /**
     * Add custom companion validation after default rules.
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
            // take guest_id from the request or from the reservation if none is passed
            $guestId = $this->input('guest_id') ?? $this->route('reservation')->guest_id;
            if (!$guestId) {
                return;
            }

            // Run custom companion validation logic
            CompanionValidator::validate($companions, $guestId, $validator);
        });
    }
}
