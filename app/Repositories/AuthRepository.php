<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'photo' => $data['photo'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(array $data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if (!Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match out records',
            ], 401);
        }

        request()->session()->regenerate();

        $user = Auth::guard('web')->user();

        return response()->json([
            'message' => 'Login succesfully',
            'user' => new UserResource($user->load('roles')),
        ]);
    }

    public function tokenLogin(array $data)
    {
        if (!Auth::guard('web')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::guard('web')->user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => new UserResource($user->load('roles')),
        ]);
    }
}
