<?php

namespace App\Validation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Carbon;

class DatetimeValidator
{
    /**
     * Runs combined date-related validations for services.
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
     * If either date is null, the validation passes silently.
     * If ends_at is not after scheduled_at, a validation error is added to the validator.
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
