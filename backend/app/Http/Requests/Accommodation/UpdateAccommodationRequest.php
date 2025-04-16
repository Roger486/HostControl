<?php

namespace App\Http\Requests\Accommodation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccommodationRequest extends FormRequest
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
            'accommodation_code' => ['sometimes', 'required', 'string', 'max:6',
                Rule::unique('accommodations', 'accommodation_code')->ignore($this->accommodation->id)
            ],
            'section' => ['sometimes', 'required', 'string', 'max:50'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'price_per_day' => ['sometimes', 'required', 'integer'], // in € cents
            'is_available' => ['sometimes', 'boolean'],
            'comments' => ['sometimes', 'nullable', 'string', 'max:255'],
            'type' => ['prohibited']
        ];
    }
}
