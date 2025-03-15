<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Companion extends Model
{
    use HasFactory;

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
     * Get the reservation that a companion is filled in.
     *
     * A companion belongs to a reservation.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
