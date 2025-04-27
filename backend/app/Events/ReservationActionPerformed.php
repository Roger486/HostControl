<?php

namespace App\Events;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservationActionPerformed
{
    use Dispatchable;
    use SerializesModels;

    public $reservation;
    public $user;
    public $actionType;
    public $logDetail;

    /**
     * Create a new event instance.
     */
    public function __construct(Reservation $reservation, User $user, string $actionType, ?string $logDetail = null)
    {
        $this->reservation = $reservation;
        $this->user = $user;
        $this->actionType = $actionType;
        $this->logDetail = $logDetail ?? __('reservation_log.log_messages.' . $actionType, ['action' => $actionType]);
    }
}
