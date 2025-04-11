<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Resources\ReservarionResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = Reservation::with('bookedBy', 'guest', 'accommodation', 'companions')->paginate(10);
        return ReservarionResource::collection($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();

        $reservation = DB::transaction(function () use ($validated) {

            $reservation = Reservation::create(Arr::except($validated, ['companions']));

            if (isset($validated['companions']) && is_array($validated['companions'])) {
                foreach ($validated['companions'] as $companionData) {
                    $reservation->companions()->create($companionData);
                }
            }

            return $reservation;
        });

        return (new ReservarionResource($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions'])))
            ->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        return new ReservarionResource($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {

        $this->authorize('update', $reservation); // use a policy, only for admins
        // "check /app/Http/Policies" for more info

        $validated = $request->validated();

        $updated = DB::transaction(function () use ($validated, $reservation) {

            if (isset($validated['companions']) && is_array($validated['companions'])) {
                // Update main reservation fields
                $reservation->update(Arr::except($validated, ['companions']));
                // If companions provided, replace them
                $reservation->companions()->delete();

                foreach ($validated['companions'] as $companionData) {
                    $reservation->companions()->create($companionData);
                }
            }
            return $reservation;
        });
        return new ReservarionResource($updated->load(['bookedBy', 'guest', 'accommodation', 'companions']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();
        return response()->noContent();
    }

    public function ownReservations(Request $request)
    {
        $user = $request->user();
        $reservations = $user->guestReservations()
            ->with('bookedBy', 'guest', 'accommodation', 'companions')->get();
        return ReservarionResource::collection($reservations);
    }
}
