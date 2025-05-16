<?php

namespace App\Http\Controllers;

use App\Events\ReservationActionPerformed;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Accommodation\Accommodation;
use App\Models\Reservation;
use App\Models\ReservationLog;
use App\Models\User;
use App\Notifications\ReservationConfirmed;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Get a paginated list of all reservations (admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = Reservation::with('bookedBy', 'guest', 'accommodation', 'companions')->paginate(10);
        return ReservationResource::collection($reservations);
    }

    /**
     * Create a new reservation and check availability.
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

        // Save reservation and companions inside a transaction
        $reservation = DB::transaction(function () use ($validated) {

            $reservation = Reservation::create(Arr::except($validated, ['companions']));

            if (isset($validated['companions']) && is_array($validated['companions'])) {
                foreach ($validated['companions'] as $companionData) {
                    $reservation->companions()->create($companionData);
                }
            }

            return $reservation;
        });

        // Log creation
        // Trigger event to register a new ReservationLog after creating a new Reservation
        event(new ReservationActionPerformed(
            $reservation,
            Auth::user(), // from Illuminate\Support\Facades\Auth, returns the authenticated user
            ReservationLog::ACTION_CREATE
        ));

        return (new ReservationResource($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions'])))
            ->response()->setStatusCode(201);
    }

    /**
     * Show details of a reservation (with related info).
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        return new ReservationResource($reservation->load(['bookedBy', 'guest', 'accommodation', 'companions']));
    }

    /**
     * Update a reservation. Handles status changes and companion sync.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        // TODO: Look for possible refactors on this update function
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

        $previousStatus = $reservation->status;

        $updated = DB::transaction(function () use ($validated, $reservation) {

            // Update main reservation fields
            $reservation->update(Arr::except($validated, ['companions']));

            if (isset($validated['companions']) && is_array($validated['companions'])) {
                // If companions provided, replace them
                $reservation->companions()->delete();

                foreach ($validated['companions'] as $companionData) {
                    $reservation->companions()->create($companionData);
                }
            }
            return $reservation;
        });

        // Determine the specific log action based on status change
        $logAction = ReservationLog::ACTION_UPDATE;

        // Decide log type based on status change
        $statusToActionMap = [
            Reservation::STATUS_CANCELLED => ReservationLog::ACTION_CANCEL,
            Reservation::STATUS_CHECKED_IN => ReservationLog::ACTION_CHECK_IN,
            Reservation::STATUS_CHECKED_OUT => ReservationLog::ACTION_CHECK_OUT,
            Reservation::STATUS_CONFIRMED => ReservationLog::ACTION_CONFIRM,
            Reservation::STATUS_PENDING => ReservationLog::ACTION_TO_PENDING
        ];

        if (isset($validated['status']) && $validated['status'] !== $previousStatus) {
            $logAction = $statusToActionMap[$validated['status']] ?? ReservationLog::ACTION_UPDATE;
            if ($logAction === ReservationLog::ACTION_CONFIRM) {
                $reservation->guest->notify(new ReservationConfirmed($reservation));
            }
        }

        // Trigger event to register the ReservationLog based on action
        event(new ReservationActionPerformed(
            $reservation,
            Auth::user(), // from Illuminate\Support\Facades\Auth, returns the authenticated user
            $logAction,
            $validated['log_detail']
        ));

        return new ReservationResource($updated->load(['bookedBy', 'guest', 'accommodation', 'companions']));
    }

    /**
     * Permanently delete a reservation from the database.
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();
        return response()->noContent();
    }

    /**
     * Get reservations made by the authenticated user.
     */
    public function ownReservations(Request $request)
    {
        $user = $request->user();
        $reservations = $user->guestReservations()
            ->with('bookedBy', 'guest', 'accommodation', 'companions')->get();
        return ReservationResource::collection($reservations);
    }


    /**
     * Get reservations for a specific user (admin only).
     */
    public function getByGuest(User $user)
    {
        $this->authorize('viewAny', Reservation::class);

        $reservations = $user->guestReservations()
        ->with('bookedBy', 'guest', 'accommodation', 'companions')->get();
        return ReservationResource::collection($reservations);
    }
}
