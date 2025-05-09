<?php

namespace App\Listeners;

use App\Events\ReservationActionPerformed;
use App\Models\ReservationLog;

class LogReservationAction
{
    /**
     * Create the event listener.
     *
     * This constructor is empty, but can be used to inject services if needed.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event and store a log entry.
     *
     * @param ReservationActionPerformed $event
     * @return void
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
