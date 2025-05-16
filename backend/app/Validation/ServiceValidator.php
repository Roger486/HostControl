<?php

namespace App\Validation;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Validator;
use App\Validation\DatetimeValidator;

/**
 * Class ServiceValidator
 *
 * Handles service-related business validations, such as availability by time and remaining slots.
 */
class ServiceValidator
{
    /**
     * Validates that the service can be attached to a reservation.
     *
     * Checks:
     * - The service is still valid based on scheduling constraints.
     * - The requested amount does not exceed the remaining available slots.
     *
     * @param Service $service The service being validated.
     * @param int $amount The quantity requested.
     * @param Validator $validator Laravel's validator instance.
     * @return void
     */
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
}
