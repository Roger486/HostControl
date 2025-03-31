<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
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
        return response()->json($reservations, 200);
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

        return response()->json($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        return response()->json($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions']), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {

        $this->authorize('update', $reservation); // use a policy, only for admins
        // "check /app/Http/Policies" for more info

        $reservation->update($request->all());

        if ($request->has('companions') && is_array($request->companions)) {
            $reservation->companions()->delete();

            foreach ($request->companions as $companionData) {
                $reservation->companions()->create($companionData);
            }
        }
        return response()->json($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions']), 200);
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
}
