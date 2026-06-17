<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $perPage = request()->query('size');
        $fields = ['*'];
        $user = $this->userService->getAll($fields ?: ['*'], $perPage);

        return UserResource::collection($user);
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $user = $this->userService->getById($id, $fields);

            return response()->json(new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User tidak ditemukan',
            ], 404);
        }
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return response()->json(new UserResource($user), 201);
    }

    public function update(UserRequest $request, int $id)
    {
        try {
            $user = $this->userService->update($id, $request->validated());

            return response()->json(new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'user tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->userService->delete($id);

            return response()->json([
                'message' => 'user berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'user tidak ditemukan',
            ], 404);
        }
    }
}
