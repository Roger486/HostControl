<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationLogResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationLogController extends Controller
{
    /**
     * Show a paginated list of logs for a specific reservation.
     *
     * @param Reservation $reservation The reservation whose logs are being retrieved.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Reservation $reservation)
    {
        $this->authorize('viewAny', Reservation::class);
        $reservationLogs = $reservation->reservationLogs()->with('user')->paginate(10);

        return ReservationLogResource::collection($reservationLogs);
    }
}
