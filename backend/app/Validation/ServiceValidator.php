<?php

namespace App\Validation;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Validator;

class ServiceValidator
{
    public static function validateServiceAttachability(Service $service, int $amount, Validator $validator): void
    {
        DatetimeValidator::validateIsCurrentlyAvailable($service, $validator);

        $used = DB::table('reservation_service')
            ->where('service_id', $service->id)
            ->sum('amount');

        $available = $service->available_slots - $used;

        if ($amount > $available) {
            $validator->errors()->add(
                'amount',
                __('validation.custom.service.not_enough_slots', ['available' => $available])
            );
        }
    }

    /**
     * Validates that the service is still available based on current time and its scheduling constraints.
     *
     * @param \App\Models\Service $service
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    public static function validateTimeWindow(Service $service, Validator $validator): void
    {
        DatetimeValidator::validateIsCurrentlyAvailable($service, $validator);
    }
}
