<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\AttachServiceRequest;
use App\Http\Requests\Service\DetachServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Reservation;

class ReservationServiceController extends Controller
{
    /**
     * Attach a service to a reservation with a specific amount (admin).
     */
    public function attachService(AttachServiceRequest $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validated();

        $reservation->attachServiceWithAmount($validated['service_id'], $validated['amount']);

        $serviceAttached = $reservation->services()
            ->where('services.id', $validated['service_id'])
            ->first();
        return new ServiceResource($serviceAttached);
    }

    /**
     * Detach a service from a reservation (admin).
     */
    public function detachService(DetachServiceRequest $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $data = $request->validated();
        $reservation->services()->detach($data['service_id']);

        return response()->noContent();
    }

    /**
     * Get the services attached to the reservation (for current user/guest).
     */
    public function ownAttachedServices(Reservation $reservation)
    {
        $this->authorize('viewOwn', $reservation);

        return ServiceResource::collection($reservation->services);
    }

    /**
     * Attach a service to the reservation by the owner/client.
     */
    public function attachOwnService(AttachServiceRequest $request, Reservation $reservation)
    {
        $this->authorize('manageOwn', $reservation);

        $validated = $request->validated();

        $reservation->attachServiceWithAmount($validated['service_id'], $validated['amount']);

        $serviceAttached = $reservation->services()
            ->where('services.id', $validated['service_id'])
            ->first();
        return new ServiceResource($serviceAttached);
    }
}
