<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationServiceController extends Controller
{
    public function attachService(Request $request, $reservationId)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'amount' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::findOrFail($reservationId);
        $service = Service::findOrFail($request->service_id);

        $totalReserved = DB::table('reservation_service')
            ->where('service_id', $service->id)
            ->sum('amount');

        $available = $service->available_slots - $totalReserved;

        if ($request->amount > $available) {
            return response()->json([
                'error' => 'No hay suficientes unidades disponibles',
                'available' => $available
            ], 400);
        }

        $reservation->services()->syncWithoutDetaching([
            $service->id => [
                'amount' => $request->amount,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        return response()->json(['message' => 'Servicio añadido correctamente']);
    }

    public function detachService(Request $request, $reservationId)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $reservation = Reservation::findOrFail($reservationId);
        $reservation->services()->detach($request->service_id);

        return response()->json(['message' => 'Servicio eliminado de la reserva']);
    }
}
