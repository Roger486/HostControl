<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationLog extends Model
{
    use HasFactory;

    // Reservation Log Actions
    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_CHECK_IN = 'check_in';
    public const ACTION_CHECK_OUT = 'check_out';
    public const ACTION_CONFIRM = 'confirm';
    public const ACTION_TO_PENDING = 'to_pending';

    public const ACTIONS = [
        self::ACTION_CREATE,
        self::ACTION_UPDATE,
        self::ACTION_CANCEL,
        self::ACTION_CHECK_IN,
        self::ACTION_CHECK_OUT,
        self::ACTION_CONFIRM,
        self::ACTION_TO_PENDING
    ];

    protected $fillable = [
        'user_id',
        'reservation_id',
        'action_type',
        'log_detail'
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
