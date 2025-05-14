<?php

namespace App\Validation;

use App\Models\Service;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;

// TODO: review possible DRY, SRP or KISS oportunities
/**
 * Class DatetimeValidator
 *
 * Handles validation rules related to service scheduling and availability dates.
 */
class DatetimeValidator
{
    /**
     * Validates if a service is currently available based on its date fields.
     *
     * @param \App\Models\Service $service
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    public static function validateIsCurrentlyAvailable(Service $service, Validator $validator): void
    {
        $now = Carbon::now();

        if (self::isNowAfter($service->available_until)) {
            $validator->errors()->add(
                'service_id',
                __('validation.custom.service.unavailable_due_to_available_until')
            );
        }

        if (self::isNowAfter($service->scheduled_at)) {
            $validator->errors()->add(
                'service_id',
                __('validation.custom.service.unavailable_due_to_scheduled_at')
            );
        }

        if (self::isNowAfter($service->ends_at)) {
            $validator->errors()->add(
                'service_id',
                __('validation.custom.service.unavailable_due_to_ended')
            );
        }
    }

    /**
     * Runs combined date-related validations on service date fields.
     *
     * @param string|null $availableUntil
     * @param string|null $endsAt
     * @param string|null $scheduledAt
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    public static function validateServicesDates(
        ?string $availableUntil,
        ?string $endsAt,
        ?string $scheduledAt,
        Validator $validator
    ): void {
        self::validateAvailableUntil($availableUntil, $scheduledAt, $validator);
        self::validateEndsAfterScheduled($endsAt, $scheduledAt, $validator);
        self::validateAvailableUntilBeforeEndsAt($availableUntil, $endsAt, $validator);
    }

    /**
     * Validates that available_until is not after scheduled_at.
     *
     * @param string|null $availableUntil
     * @param string|null $scheduledAt
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    private static function validateAvailableUntil(
        ?string $availableUntil,
        ?string $scheduledAt,
        Validator $validator
    ): void {
        if (!self::isBeforeOrEqual($availableUntil, $scheduledAt)) {
            $validator->errors()->add(
                'available_until',
                __('validation.custom.service.available_until_after_scheduled_at')
            );
        }
    }

    /**
     * Validates that available_until is not after ends_at.
     *
     * @param string|null $availableUntil
     * @param string|null $endsAt
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    private static function validateAvailableUntilBeforeEndsAt(
        ?string $availableUntil,
        ?string $endsAt,
        Validator $validator
    ): void {
        if (!self::isBeforeOrEqual($availableUntil, $endsAt)) {
            $validator->errors()->add(
                'available_until',
                __('validation.custom.service.available_until_after_ends_at')
            );
        }
    }


    /**
     * Validates that the end date (ends_at) is after the scheduled start date (scheduled_at).
     *
     * If either date is null, the validation is skipped (considered valid).
     * If ends_at is not after scheduled_at, a validation error is added.
     *
     * @param string|null $endsAt      The end date of the service.
     * @param string|null $scheduledAt The scheduled start date of the service.
     * @param \Illuminate\Contracts\Validation\Validator $validator The validator instance to attach errors to.
     * @return void
     */
    private static function validateEndsAfterScheduled(
        ?string $endsAt,
        ?string $scheduledAt,
        Validator $validator
    ): void {
        if (!self::isAfter($endsAt, $scheduledAt)) {
            $validator->errors()->add(
                'ends_at',
                __('validation.custom.service.ends_before_scheduled_at')
            );
        }
    }

    /**
     * Check if the current moment is after the given date.
     */
    private static function isNowAfter(?string $date): bool
    {
        return self::isAfter(Carbon::now(), $date);
    }

    /**
     * Check if dateA is before or equal to dateB.
     *
     * @param string|null $dateA
     * @param string|null $dateB
     * @return bool True if dateA <= dateB, or if any date is null.
     */
    private static function isBeforeOrEqual(?string $dateA, ?string $dateB): bool
    {
        if (!$dateA || !$dateB) {
            return true;
        }
        return Carbon::parse($dateA)->lessThanOrEqualTo(Carbon::parse($dateB));
    }

    /**
     * Check if dateA is strictly after dateB.
     *
     * @param string|null $dateA
     * @param string|null $dateB
     * @return bool True if dateA > dateB, or if any date is null.
     */
    private static function isAfter(?string $dateA, ?string $dateB): bool
    {
        if (!$dateA || !$dateB) {
            return true;
        }
        return Carbon::parse($dateA)->greaterThan(Carbon::parse($dateB));
    }
}
