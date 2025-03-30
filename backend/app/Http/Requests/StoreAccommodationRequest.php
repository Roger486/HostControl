<?php

namespace App\Http\Requests;

use App\Models\Accommodation\Accommodation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccommodationRequest extends FormRequest
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
            'accommodation_code' => ['required', 'string', 'max:6', 'unique:accommodations,accommodation_code'],
            'section' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1'],
            'price_per_day' => ['required', 'integer'], // in € cents
            'is_available' => ['boolean'],
            'comments' => ['nullable', 'string', 'max:255'],
            'type' => [
                'required',
                Rule::in(Accommodation::TYPES)
            ]
        ];
    }
}
