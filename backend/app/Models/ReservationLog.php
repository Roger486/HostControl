<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_id',
        'action_type',
        'comments'
    ];

    protected $casts = [
        'action_type' => 'string' // Ensures the Enum `action_type` is always stored and retrieved as a string
    ];

    /**
     * Get the reservation linked to this log entry.
     *
     * A log entry belongs to a reservation.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    /**
     * Get the user that performed the action.
     *
     * A log entry belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
