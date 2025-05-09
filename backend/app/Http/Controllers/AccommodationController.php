<?php

namespace App\Http\Controllers;

use App\Http\Requests\Accommodation\IndexAccommodationRequest;
use App\Http\Requests\Accommodation\StoreAccommodationRequest;
use App\Http\Requests\Accommodation\UpdateAccommodationRequest;
use App\Http\Resources\AccommodationResource;
use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AccommodationController extends Controller
{
    /**
     * Return a list of accommodations with filters and pagination.
     */
    public function index(IndexAccommodationRequest $request)
    {
        // Validations
        $validated = $request->validated();

        // Query and filters
        $query = Accommodation::query();
        Accommodation::applyFilters($query, $validated);

        // Add relations
        $query->with(Accommodation::withAllRelations());

        return AccommodationResource::collection($query->paginate(10));
    }

    /**
     * Create a new accommodation (with dynamic subtype support).
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
     * Show a single accommodation with all related data.
     */
    public function show(Accommodation $accommodation)
    {
        return new AccommodationResource($accommodation->load(Accommodation::withAllRelations()));
    }


    /**
     * Update an existing accommodation (supports dynamic subtypes).
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
     * Delete an accommodation and its related data.
     */
    public function destroy(Accommodation $accommodation)
    {
        $this->authorize('delete', $accommodation);

        $accommodation->delete();

        return response()->noContent();
    }

    /**
     * Upload an image and link it to a specific accommodation.
     */
    public function uploadImage(Request $request, Accommodation $accommodation)
    {
        $this->authorize('update', $accommodation);

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        $path = $request->file('image')->store('accommodations', 'public');

        $accommodation->images()->create([
            'image_path' => $path
        ]);

        return response()->json([
            'data' => [
                'url' => asset('storage/' . $path),
                'image_path' => $path
            ]
        ]);
    }

    /**
     * Delete an accommodation image from storage and database.
     */
    public function deleteImage(AccommodationImage $image)
    {
        $this->authorize('delete', $image->accommodation);

        Storage::disk('public')->delete($image->image_path);

        $image->delete();

        return response()->noContent();
    }
}
