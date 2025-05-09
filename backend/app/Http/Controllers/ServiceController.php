<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Get a list of all services.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ServiceResource::collection(Service::all());
    }

    /**
     * Create a new service.
     *
     * @param StoreServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreServiceRequest $request)
    {
        $this->authorize('create', Service::class);

        $validated = $request->validated();

        $service = Service::create($validated);

        return (new ServiceResource($service))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a specific service.
     *
     * @param Service $service
     * @return ServiceResource
     */
    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    /**
     * Update a service.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return ServiceResource
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validated();

        $service->update($validated);
        return new ServiceResource($service);
    }


    /**
     * Delete a service.
     *
     * @param Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();
        return response()->noContent();
    }
}
