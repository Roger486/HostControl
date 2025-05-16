<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class DetachServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for detaching a service from a reservation.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // The service ID must exist in the services table
            'service_id' => ['required', 'exists:services,id'],
        ];
    }
    // TODO: add extra logic to avoid detach services depending on reservation status
}
