<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSelfRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return response()->json(User::paginate(10), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // with validated(), only the fields that pass validation in StoreUserRequest are passed
        // this avoid malicious users sending a json to create an admin role user creation
        $user = User::create($request->validated());

        return response()->json($user, 201);
    }

    /**
     * Show the specified user.
     *
     * This method uses Route Model Binding, so we get the user automatically
     * without calling User::find($id).
     *
     * If the user does not exist, Laravel will return a 404 error automatically.
     *
     * This works because in api.php we use:
     * Route::apiResource('users', UserController::class);
     *
     * Alternative traditional method:
     *
     * public function show(string $id)
     * {
     *     $user = User::find($id);
     *     if (!$user) {
     *         return response()->json(['message' => 'User not found'], 404);
     *     }
     *     return response()->json($user, 200);
     * }
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $user->update($request->validated());
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('viewAny', $user);
        $user->delete();
        return response()->noContent();
        // same as using response()->json([], 204);
    }

    public function me(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function updateSelf(UpdateSelfRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return response()->json($user, 200);
    }
}
