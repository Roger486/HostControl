<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Companion extends Model
{
    use HasFactory;

    // Document Types
    public const DOCUMENT_DNI = 'DNI';
    public const DOCUMENT_NIE = 'NIE';
    public const DOCUMENT_PASSPORT = 'Passport';

    /**
     * Available document types for companions.
     */
    public const DOCUMENT_TYPES = [
        self::DOCUMENT_DNI,
        self::DOCUMENT_NIE,
        self::DOCUMENT_PASSPORT
    ];

    protected $fillable = [
        'reservation_id',
        'document_number',
        'document_type',
        'first_name',
        'last_name_1',
        'last_name_2',
        'birthdate'
    ];

    protected $casts = [
        'document_type' => 'string', // Ensures the Enum `document_type` is always stored and retrieved as a string
        'birthdate' => 'date'
    ];


    /**
     * The reservation this companion is associated with.
     *
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
