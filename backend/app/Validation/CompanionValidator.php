<?php

namespace App\Validation;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;

/**
 * Class CompanionValidator
 *
 * Handles validation logic for companions in reservations.
 */
class CompanionValidator
{
    /**
     * Validate all companions in the request.
     *
     * @param array $companions List of companion data.
     * @param int $guestId ID of the main guest to check for duplicate documents.
     * @param Validator $validator The Laravel validator instance.
     * @return void
     */
    public static function validate(
        array $companions,
        int $guestId,
        Validator $validator
    ) {
        $seenDocuments = self::initSeenDocuments($guestId);

        foreach ($companions as $index => $companion) {
            // data setup
            $birthdate = $companion['birthdate'] ?? null;
            if (!$birthdate) {
                continue;
            }
            $age = Carbon::parse($birthdate)->age;

            $type = $companion['document_type'] ?? null;
            $documentNumber = $companion['document_number'] ?? null;

            if ($age < 18) {
                self::validateMinorDocumentInput($validator, $index, $type, $documentNumber);
            } else {
                self::validateAdultDocumentInput($validator, $index, $type, $documentNumber);
            }

            if ($type && $documentNumber) {
                $normalizedDoc = strtoupper(trim($documentNumber));

                self::validateDocumentFormat($validator, $index, $type, $documentNumber);
                self::validateDuplicateDocumentNumber($validator, $index, $normalizedDoc, $seenDocuments);

                $seenDocuments[] = $normalizedDoc;
            }
        }
    }

    /**
     * Initialize the list of already-seen document numbers with the guest's.
     *
     * @param int $guestId
     * @return array<string>
     */
    private static function initSeenDocuments(int $guestId): array
    {
        $guest = User::find($guestId);
        if ($guest && $guest->document_number) {
            $seenDocuments[] = strtoupper(trim($guest->document_number));
        }
        return $seenDocuments ?? [];
    }

    /**
     * Validate that a minor does not provide only one of type or document number.
     *
     * @param Validator $validator
     * @param int $index
     * @param string|null $type
     * @param string|null $documentNumber
     * @return void
     */
    private static function validateMinorDocumentInput(
        Validator $validator,
        int $index,
        ?string $type,
        ?string $documentNumber
    ) {
        if (($type && !$documentNumber) || (!$type && $documentNumber)) {
            $validator->errors()->add(
                "companions.$index.document_number",
                __('validation.custom.companions.*.document_number.both_or_none_for_minors')
            );
        }
    }

    /**
     * Validate that an adult provides both type and document number.
     *
     * @param Validator $validator
     * @param int $index
     * @param string|null $type
     * @param string|null $documentNumber
     * @return void
     */
    private static function validateAdultDocumentInput(
        Validator $validator,
        int $index,
        ?string $type,
        ?string $documentNumber
    ) {
        if (!$type || !$documentNumber) {
            $validator->errors()->add(
                "companions.$index.document_number",
                __('validation.custom.companions.*.document_number.required_for_adults')
            );
        }
    }

    /**
     * Validate the format of the provided document.
     *
     * @param Validator $validator
     * @param int $index
     * @param string $type
     * @param string $documentNumber
     * @return void
     */
    private static function validateDocumentFormat(
        Validator $validator,
        int $index,
        string $type,
        string $documentNumber
    ) {
        if ($errorMessage = DocumentValidator::validate($type, $documentNumber)) {
            $validator->errors()->add("companions.$index.document_number", $errorMessage);
        }
    }

    /**
     * Ensure that the document number has not already been used.
     *
     * @param Validator $validator
     * @param int $index
     * @param string $documentNumber
     * @param array<string> $seenDocuments
     * @return void
     */
    private static function validateDuplicateDocumentNumber(
        Validator $validator,
        int $index,
        string $documentNumber,
        array $seenDocuments
    ) {
        if (in_array($documentNumber, $seenDocuments)) {
            $validator->errors()->add(
                "companions.$index.document_number",
                __('validation.custom.companions.*.document_number.not_repeated')
            );
        }
    }
}
