<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->validated());
    }

    public function tokenLogin(LoginRequest $request)
    {
        return $this->authService->tokenLogin($request->validated());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json(new UserResource($request->user()->load('roles')));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $rules = [
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->whereNull('deleted_at'),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        $validated = $request->validate($rules);

        // Update User fields
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();

            if ($mahasiswa) {
                // Delete old photo if exists
                if ($mahasiswa->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($mahasiswa->photo);
                }
                $path = $request->file('photo')->store('photo-student', 'public');
                $mahasiswa->photo = $path;
                $mahasiswa->save();
                
                // Keep users.photo in sync
                $user->photo = $path;
            } elseif ($dosen) {
                // Delete old photo if exists
                if ($dosen->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($dosen->photo);
                }
                $path = $request->file('photo')->store('photo-lecturer', 'public');
                $dosen->photo = $path;
                $dosen->save();
                
                // Keep users.photo in sync
                $user->photo = $path;
            } else {
                // Admin or other user
                if ($user->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
                }
                $path = $request->file('photo')->store('photo-user', 'public');
                $user->photo = $path;
            }
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => new UserResource($user->load('roles'))
        ]);
    }
}
