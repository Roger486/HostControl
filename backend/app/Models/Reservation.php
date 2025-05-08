<?php

namespace App\Models;

use App\Models\Accommodation\Accommodation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    // Reservation Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_CHECKED_OUT = 'checked_out';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_CANCELLED,
        self::STATUS_CHECKED_IN,
        self::STATUS_CHECKED_OUT
    ];

    protected $fillable = [
        'booked_by_id',
        'guest_id',
        'accommodation_id',
        'check_in_date',
        'check_out_date',
        'status',
        'comments'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'status' => 'string' // Ensures the Enum `status` is always stored and retrieved as a string
    ];

    /**
     * Get the user that booked this reservation.
     *
     * A reservation is booked by a user.
     */
    public function bookedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by_id');
    }

    /**
     * Get the user that will be staying for this reservation.
     *
     * A reservation has a guest user.
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /**
     * Get the accommodation that is linked to this reservation.
     *
     * A reservation belongs to an accommodation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    /**
     * Get all companions linked to this reservation.
     *
     * A reservation can have multiple companions.
     */
    public function companions(): HasMany
    {
        return $this->hasMany(Companion::class, 'reservation_id');
    }

    /**
     * Get the Reservation Logs performed on this reservation.
     *
     * A reservation has many log entries.
     */
    public function reservationLogs(): HasMany
    {
        return $this->hasMany(ReservationLog::class, 'reservation_id');
    }

    /**
     * Get the Reservation Services attached to this reservation.
     *
     * A reservation has many different services.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'reservation_service')
                    ->using(ReservationService::class)
                    ->withPivot('amount')
                    ->withTimestamps();
    }

    /**
     * Attach the service with ID $serviceId to this Reservation with specified $amount.
     */
    public function attachServiceWithAmount($serviceId, $amount)
    {
        $this->services()->syncWithoutDetaching([
            $serviceId => [
                'amount' => $amount,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Determine whether services can be added to this reservation,
     * based on its current status.
     *
     * Services can only be added when the reservation is in
     * 'confirmed', or 'checked_in' status.
     *
     * @return bool True if services can be added; false otherwise.
     */
    public function canAddServices(): bool
    {
        return
            $this->status === self::STATUS_CONFIRMED
            || $this->status === self::STATUS_CHECKED_IN;
    }
}
