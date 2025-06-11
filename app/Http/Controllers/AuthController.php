<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(AuthRequest $request)
    {
        $validated = $request->validated();

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $validated['name'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            $validated['avatar'] = $path;
        }

        // Create the user
        $user = User::create($validated);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::jsonResponse(
            true,
            'User registered successfully',
            [
                'user' => new AuthResource($user),
                'token' => $token
            ],
            201
        );
    }

    /**
     * Login user and create token
     */
    public function login(AuthRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return ResponseHelper::jsonResponse(
                false,
                'Invalid credentials',
                null,
                401
            );
        }

        $user = User::where('email', $validated['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::jsonResponse(
            true,
            'Logged in successfully',
            [
                'user' => new AuthResource($user),
                'token' => $token
            ],
            200
        );
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout()
    {
      auth()->user()->currentAccessToken()->delete();
        return ResponseHelper::jsonResponse(
            true,
            'Logged out successfully',
            null,
            200
        );
    }
}