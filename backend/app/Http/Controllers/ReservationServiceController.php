<?php

namespace App\Http\Controllers;

use App\Http\Requests\DetachServiceRequest;
use App\Http\Requests\Service\AttachServiceRequest;
use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationServiceController extends Controller
{
    public function attachService(AttachServiceRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        $reservation->attachServiceWithAmount($validated['service_id'], $validated['amount']);

        $serviceAttached = $reservation->services()
            ->where('services.id', $validated['service_id'])
            ->first();
        return response()->json($serviceAttached, 200);
    }

    public function detachService(DetachServiceRequest $request, Reservation $reservation)
    {
        $data = $request->validated();
        $reservation->services()->detach($data['service_id']);

        return response()->noContent();
    }
}
