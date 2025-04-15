<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccommodationRequest;
use App\Http\Requests\UpdateAccommodationRequest;
use App\Http\Resources\AccommodationResource;
use App\Models\Accommodation\Accommodation;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Accommodation::query();

        // Validations
        $request->validate([
            'type' => [Rule::in(Accommodation::TYPES)],
            'min_capacity' => ['integer', 'min:1', 'lte:max_capacity'],
            'max_capacity' => ['integer', 'gte:min_capacity'],
            'check_in_date' => ['date_format:Y-m-d', 'date', 'after:today', 'required_with:check_out_date'],
            'check_out_date' => ['date_format:Y-m-d', 'date', 'after:check_in_date', 'required_with:check_in_date'],
        ]);

        // Filters
        // TODO: work on scopes on the model or even a whole new class for index filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $request->min_capacity);
        }

        if ($request->filled('max_capacity')) {
            $query->where('capacity', '<=', $request->max_capacity);
        }

        if ($request->filled('check_in_date') && $request->filled('check_out_date')) {
            $checkInDate = Carbon::parse($request->check_in_date);
            $checkOutDate = Carbon::parse($request->check_out_date);

            $query->whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate) {
                $query->where('check_in_date', '<', $checkOutDate)
                    ->where('check_out_date', '>', $checkInDate)
                    ->whereNot('status', Reservation::STATUS_CANCELLED);
            });
        }

        // Add relations
        $query->with(Accommodation::withAllRelations());

        return AccommodationResource::collection($query->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccommodationRequest $request)
    {

        $this->authorize('create', Accommodation::class);

        // stores the validated Accomodation fields
        $validated = $request->validated();

        // Transforms the type into UpperCamelCase to match the class format and stores the class as string
        $accommodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($validated['type']);

        // Get subtype-specific validation rules via HasDynamicValidation interface
        // Validator::make(...) creates a new validation instance
        // ->validate() executes it and throws a ValidationException on failure
        // Laravel automatically captures the exception and returns a 422 response with the error messages
        $subClassValidator = Validator::make($request->all(), $accommodationTypeClass::rules());
        $subClassValidator->validate();

        // Add validated fields from the subclass
        $validated = array_merge($validated, $subClassValidator->validated());

        // start a transaction in case the subclass creation fails
        $accommodation = DB::transaction(function () use ($validated, $accommodationTypeClass) {
            // Get only the base accommodation attributes that are validated and create an Accommodation
            $accommodation = Accommodation::create(Arr::only($validated, Accommodation::BASE_ATTRIBUTES));

            // get subtype attributes
            $subclassData = Arr::except($validated, Accommodation::BASE_ATTRIBUTES);
            $subclassData['accommodation_id'] = $accommodation->id;

            $accommodationTypeClass::create($subclassData);

            return $accommodation;
        });

        return (new AccommodationResource($accommodation->load(Accommodation::withAllRelations())))
            ->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {
        return new AccommodationResource($accommodation->load(Accommodation::withAllRelations()));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        // stores the validated Accomodation fields
        $validated = $request->validated();

        // Transforms the type into UpperCamelCase to match the class format and stores the class as string
        $accommodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($accommodation->type);

        // Get subtype-specific validation rules via HasDynamicValidation interface
        // Validator::make(...) creates a new validation instance
        // ->validate() executes it and throws a ValidationException on failure
        // Laravel automatically captures the exception and returns a 422 response with the error messages
        $subClassValidator = Validator::make($request->all(), $accommodationTypeClass::rules('update'));
        $subClassValidator->validate();

        // Add validated fields from the subclass
        $validated = array_merge($validated, $subClassValidator->validated());

        // start a transaction in case the subclass update fails
        $updated = DB::transaction(function () use ($validated, $accommodation) {
            // Get only the base accommodation attributes that are validated and update Accommodation
            $accommodation->update(Arr::only($validated, Accommodation::BASE_ATTRIBUTES));

            // get subtype attributes
            $subclassData = Arr::except($validated, Accommodation::BASE_ATTRIBUTES);
            $subclassData['accommodation_id'] = $accommodation->id;

            // Get related instance (e.g. $accommodation->house, room, etc.)
            $subtypeInstance = $accommodation->{$accommodation->type};

            $subtypeInstance->update($subclassData);

            return $accommodation;
        });

        return new AccommodationResource($accommodation->load(Accommodation::withAllRelations()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        $this->authorize('delete', $accommodation);

        $accommodation->delete();

        return response()->noContent();
    }
}
