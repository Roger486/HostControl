<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccommodationRequest;
use App\Http\Requests\UpdateAccommodationRequest;
use App\Models\Accommodation\Accommodation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accommodations = Accommodation::with(Accommodation::withAllRelations())->paginate(10);
        return response()->json($accommodations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccommodationRequest $request)
    {

        $this->authorize('create', Accommodation::class);

        $validated = $request->validated();
        // Transforms the type into UpperCamelCase to match the class format and stores the class as string
        $accommodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($validated['type']);

        // $accommodationTypeClass::rules() is the method from the HasDynamicValidation interface
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

        return response()->json($accommodation->load(Accommodation::withAllRelations()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {
        return response()->json($accommodation->load(Accommodation::withAllRelations()), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccommodationRequest $request, Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        $validated = $request->validated();

        // start a transaction in case the subclass update fails
        $updated = DB::transaction(function () use ($validated, $accommodation) {
            // Get only the base accommodation attributes that are validated and update Accommodation
            $accommodation->update(Arr::only($validated, Accommodation::BASE_ATTRIBUTES));

            // Transforms the type into UpperCamelCase to match the class format and stores the class as string
            $accommodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($accommodation->type);

            // get subtype attributes
            $subclassData = Arr::except($validated, Accommodation::BASE_ATTRIBUTES);
            $subclassData['accommodation_id'] = $accommodation->id;

            // Get related instance (e.g. $accommodation->house, room, etc.)
            $subtypeInstance = $accommodation->{$accommodation->type};

            $subtypeInstance->update($subclassData);

            return $accommodation;
        });

        return response()->json($accommodation->load(Accommodation::withAllRelations()), 200);
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
