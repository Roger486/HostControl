<?php

namespace App\Http\Requests\Service;

use App\Models\Reservation;
use App\Models\Service;
use App\Validation\ServiceValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AttachServiceRequest extends FormRequest
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
            'service_id' => ['required', 'exists:services,id'],
            'amount' => ['required', 'integer', 'min:1']
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // get relevant data
            $serviceId = $this->input('service_id');
            $amount = (int) $this->input('amount');
            $reservation = $this->route('reservation');

            // Exit early if critical inputs are missing
            if (!$serviceId || !$amount || !$reservation) {
                $validator->errors()->add(
                    'general',
                    __('validation.custom.service.invalid_request_data')
                );
                return;
            }

            // Check if the reservation is in an status that allows service attachment
            if (! $reservation->canAddServices()) {
                $validator->errors()->add(
                    'reservation',
                    __('validation.custom.reservation.status_doesnt_allow_service_attachment', ['status' => $reservation->status])
                );
            }

            $service = Service::find($serviceId);
            if ($service) {
                ServiceValidator::validateServiceAttachability($service, $amount, $validator);
            }
        });
    }
}
