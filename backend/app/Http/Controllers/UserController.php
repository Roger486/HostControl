<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSelfRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = User::paginate(10);
        return UserResource::collection($users);
    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $request->validate([
            'email' => ['email', 'max:255', 'required_without:document_number'],
            'document_number' => ['string', 'max:20', 'required_without:email']
        ]);

        $email = $request->email;
        $document_number = $request->document_number;
        if ($email) {
            $users = User::where('email', $email)->get();
        } elseif ($document_number) {
            $users = User::where('document_number', $document_number)->get();
        }

        if ($users->isEmpty()) {
            throw ValidationException::withMessages([
                'search' => [__('validation.custom.search.no_results')],
            ]);
        }

        $response = [
            'data' => new UserResource($users->first()),
        ];

        // Both email and document_number are unique in the DB
        // This is an extra security measure to protect data integrity
        if ($users->count() > 1) {
            $response['meta'] = ['warning' => __('validation.custom.search.multiple_results')];
        }

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // with validated(), only the fields that pass validation in StoreUserRequest are passed
        // this avoid malicious users sending a json to create an admin role user creation
        $user = User::create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->noContent();
        // same as using response()->json([], 204);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return new UserResource($user);
    }

    public function updateSelf(UpdateSelfRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return new UserResource($user);
    }
}
