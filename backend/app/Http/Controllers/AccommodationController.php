<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccommodationRequest;
use App\Http\Requests\UpdateAccommodationRequest;
use App\Models\Accommodation\Accommodation;
use Illuminate\Support\Str;

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
        // TODO: error control to avoid wrong or null type before creating Accommodation

        $this->authorize('create', Accommodation::class);
        $accommodation = Accommodation::create($request->only([
            'accommodation_code',
            'section',
            'capacity',
            'price_per_day',
            'is_available',
            'comments',
            'type'
        ]));

        // Transforms the type into UpperCamelCase to match the class format and stores the class as string
        $accomodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($request->type);

        $subclassData = array_merge(
            ['accommodation_id' => $accommodation->id],
            $request->except(
                ['accommodation_code', 'section', 'capacity', 'price_per_day', 'is_available', 'comments', 'type']
            )
        );

        $accomodationTypeClass::create($subclassData);

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

        // TODO: error control on type to avoid any modification before updating anything

        $accommodation->update($request->only([
            'accommodation_code',
            'section',
            'capacity',
            'price_per_day',
            'is_available',
            'comments',
            'type'
        ]));

        $accomodationTypeClass = 'App\\Models\\Accommodation\\' . Str::studly($accommodation->type);
        $accomodationTypeClass::where('accommodation_id', $accommodation->id)->first()
            ->update($request->except(
                'accommodation_code',
                'section',
                'capacity',
                'price_per_day',
                'is_available',
                'comments',
                'type'
            ));

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
