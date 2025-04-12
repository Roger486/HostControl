<?php

namespace App\Http\Requests;

use App\Models\Companion;
use App\Models\Reservation;
use App\Validation\CompanionValidator;
use App\Validation\DocumentValidator;
use Carbon\Carbon;
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guest_id' => ['sometimes', 'required', 'exists:users,id'],
            'accommodation_id' => ['sometimes', 'required', 'exists:accommodations,id'],
            'check_in_date' => ['sometimes', 'required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['sometimes', 'required', 'date', 'after:check_in_date'],
            'status' => ['sometimes', Rule::in(Reservation::STATUSES)],
            'comments' => ['nullable', 'string', 'max:255'],

            // Companions validation rules
            'companions' => ['sometimes', 'array'],
            'companions.*.document_type' => ['sometimes', 'required', Rule::in(Companion::DOCUMENT_TYPES)],
            'companions.*.document_number' => ['sometimes', 'required', 'string', 'max:20'],
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

            CompanionValidator::validate($companions, $guestId, $validator);
        });
    }
}
