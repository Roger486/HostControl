<?php

namespace App\Http\Requests\Service;

use App\Validation\DatetimeValidator;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'price' => ['sometimes', 'required', 'integer', 'min:0'],
            'daily_price' => ['sometimes', 'required', 'integer', 'min:0'],
            'available_slots' => ['required', 'integer', 'min:1'],
            'comments' => ['nullable', 'string'],
            'available_until' => ['nullable', 'date', 'after_or_equal:today'],
            'scheduled_at' => ['nullable', 'date', 'after_or_equal:today'],
            'ends_at' => ['nullable', 'date', 'after:scheduled_at', 'after_or_equal:today']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'price' => $this->input('price', 0),
            'daily_price' => $this->input('daily_price', 0),
        ]);
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $availableUntil = $this->input('available_until');
            $scheduledAt = $this->input('scheduled_at');
            $endsAt = $this->input('ends_at');

            DatetimeValidator::validateServicesDates(
                $availableUntil,
                $endsAt,
                $scheduledAt,
                $validator
            );
        });
    }
}
