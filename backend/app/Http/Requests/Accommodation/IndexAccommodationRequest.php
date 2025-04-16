<?php

namespace App\Http\Requests\Accommodation;

use App\Models\Accommodation\Accommodation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexAccommodationRequest extends FormRequest
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
            'type' => [Rule::in(Accommodation::TYPES)],
            'min_capacity' => ['integer', 'min:1', 'lte:max_capacity', 'required_with:max_capacity'],
            'max_capacity' => ['integer', 'gte:min_capacity', 'required_with:min_capacity'],
            'check_in_date' => ['date_format:Y-m-d', 'date', 'after:today', 'required_with:check_out_date'],
            'check_out_date' => ['date_format:Y-m-d', 'date', 'after:check_in_date', 'required_with:check_in_date'],
        ];
    }
}
