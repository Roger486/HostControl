<?php

namespace App\Events;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event triggered when a user performs an action on a reservation.
 */
class ReservationActionPerformed
{
    use Dispatchable;
    use SerializesModels;

    public $reservation;
    public $user;
    public $actionType;
    public $logDetail;


    /**
     * Set up the event with reservation, user and action details.
     *
     * @param Reservation $reservation The reservation being changed.
     * @param User $user The user performing the action.
     * @param string $actionType The type of action (e.g., created, updated).
     * @param string|null $logDetail Optional custom message. If not provided, uses default translation.
     */
    public function __construct(Reservation $reservation, User $user, string $actionType, ?string $logDetail = null)
    {
        $this->reservation = $reservation;
        $this->user = $user;
        $this->actionType = $actionType;
        $this->logDetail = $logDetail ?? __('reservation_log.log_messages.' . $actionType, ['action' => $actionType]);
    }
}
