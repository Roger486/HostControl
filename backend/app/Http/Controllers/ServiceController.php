<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Service::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'available_slots' => 'required|integer|min:1',
            'comments' => 'nullable|string',
        ]);

        return Service::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return $service;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:50',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|integer|min:0',
            'available_slots' => 'sometimes|required|integer|min:0',
            'comments' => 'nullable|string',
        ]);

        $service->update($validated);
        return $service;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->noContent();
    }
}
