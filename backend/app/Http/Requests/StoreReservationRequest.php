<?php

namespace App\Http\Requests;

use App\Models\Companion;
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
            'companions.*.document_type' => [Rule::in(Companion::DOCUMENT_TYPES)],
            // TODO: document_number -> withValidator() -> depends on document_type
            'companions.*.document_number' => ['string', 'max:20'],
            // TODO: avoid repeated document_number on same reservation or like guest_id
            'companions.*.first_name' => ['required', 'string', 'max:100'],
            'companions.*.last_name_1' => ['required', 'string', 'max:100'],
            'companions.*.last_name_2' => ['nullable', 'string', 'max:100'],
            'companions.*.birthdate' => ['required', 'date', 'date_format:Y-m-d', 'before:today']
        ];
    }
}
