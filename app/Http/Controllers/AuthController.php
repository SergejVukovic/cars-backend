<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginAuthRequest;
use App\Http\Requests\Auth\LogoutAuthRequest;
use App\Http\Requests\Auth\StoreAuthRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function store (StoreAuthRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $new_user = (new User)->create($validated);
        $token = $new_user->createToken($new_user->email);
        $new_user->access_token = $token->plainTextToken;

        return response()->json($new_user);
    }

    public function login (LoginAuthRequest $request): JsonResponse
    {
        $user = (new User)->where('email', $request->email)->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($user->email);
        $user->access_token = $token->plainTextToken;

        return response()->json($user);
    }

    public function logout(LogoutAuthRequest $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "status" => "success"
        ]);
    }
}
