<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationLogResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Reservation $reservation)
    {
        $this->authorize('viewAny', Reservation::class);
        $reservationLogs = $reservation->reservationLogs()->with('user')->paginate(10);

        return ReservationLogResource::collection($reservationLogs);
    }
}
