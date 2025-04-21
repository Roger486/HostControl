<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\ReservarionResource;
use App\Models\Accommodation\Accommodation;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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

        // Validate if the accommodation is free by dates
        // 1st get dates and accommodation_id
        $checkInDate = Carbon::parse($validated['check_in_date']);
        $checkOutDate = Carbon::parse($validated['check_out_date']);
        $accommodation = Accommodation::find($validated['accommodation_id']);
        // 2nd check if the accomodation is free to book is those dates
        if (!$accommodation->isAvailableBetweenDates($checkInDate, $checkOutDate)) {
            throw ValidationException::withMessages([
                'accommodation_id' => [__('validation.custom.reservation.not_free_by_date')],
            ]);
        }

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

        if (isset($validated['check_in_date'], $validated['check_out_date'])) {
            // Validate if the accommodation is free by dates
            // 1st get dates and accommodation
            $checkInDate = Carbon::parse($validated['check_in_date']);
            $checkOutDate = Carbon::parse($validated['check_out_date']);
            $accommodation = $reservation->accommodation;
            // 2nd check if the accomodation is free to book is those dates
            if (!$accommodation->isAvailableBetweenDates($checkInDate, $checkOutDate, $reservation->id)) {
                throw ValidationException::withMessages([
                    'accommodation_id' => [__('validation.custom.reservation.not_free_by_date')],
                ]);
            }
        }

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
