<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\ViewUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param ViewUserRequest $request
     * @return JsonResponse
     */
    public function show(ViewUserRequest $request): JsonResponse
    {
        return response()->json($request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if(isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyUserRequest $request
     * @return JsonResponse
     */
    public function destroy(DestroyUserRequest $request): JsonResponse
    {
        $user = $request->user();
        return response()->json($user);
    }
}
