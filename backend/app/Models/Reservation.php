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

    /**
     * Allowed status values for validation or filtering.
     */
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
     * The user who created the booking (e.g., the one who made the reservation).
     */
    public function bookedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'booked_by_id');
    }

    /**
     * The guest who will actually stay (can be different from the booker).
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guest_id');
    }

    /**
     * The accommodation assigned to this reservation.
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    /**
     * People associated with the reservation other than the main guest.
     */
    public function companions(): HasMany
    {
        return $this->hasMany(Companion::class, 'reservation_id');
    }

    /**
     * Reservation logs (audit history).
     */
    public function reservationLogs(): HasMany
    {
        return $this->hasMany(ReservationLog::class, 'reservation_id');
    }

    /**
     * Services attached to this reservation via pivot table.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'reservation_service')
                    ->using(ReservationService::class)
                    ->withPivot('amount')
                    ->withTimestamps();
    }

    /**
     * Attach a service to the reservation with the given amount.
     *
     * Note: This method uses syncWithoutDetaching(), which can lead to
     * unnecessary database writes if used repeatedly without checking.
     * Use with caution in loops or bulk operations.
     *
     * @param int|string $serviceId The ID of the service to attach.
     * @param int $amount The amount to associate with the service.
     * @return void
     */
    public function attachServiceWithAmount($serviceId, $amount)
    {
        // TODO: Consider detecting whether the pivot already exists to only set
        // 'created_at' on inserts, and only update 'updated_at' when necessary.
        $this->services()->syncWithoutDetaching([
            $serviceId => [
                'amount' => $amount,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Determine if new services can be added to this reservation.
     * Only allowed if status is 'confirmed' or 'checked_in'.
     *
     * @return bool
     */
    public function canAddServices(): bool
    {
        return
            $this->status === self::STATUS_CONFIRMED
            || $this->status === self::STATUS_CHECKED_IN;
    }
}
