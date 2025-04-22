<?php

namespace App\Listeners;

use App\Events\ReservationActionPerformed;
use App\Models\ReservationLog;

class LogReservationAction
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // Usefull to inject services, empty for now
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationActionPerformed $event): void
    {
        ReservationLog::create([
            'user_id' => $event->user->id,
            'reservation_id' => $event->reservation->id,
            'action_type' => $event->actionType,
            'log_detail' => $event->logDetail
        ]);
    }
}
