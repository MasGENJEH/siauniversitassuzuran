<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequest;
use App\Services\UserRoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRoleController extends Controller
{
    private UserRoleService $userRoleService;

    public function __construct(UserRoleService $userRoleService)
    {
        $this->userRoleService = $userRoleService;
    }

    public function assignRole(UserRoleRequest $request)
    {
        $user = $this->userRoleService->assignRole(
            $request->validated()['user_id'],
            $request->validated()['role_id']
        );

        return response()->json([
            'message' => 'Role telah ditambahkan',
            'data' => $user,
        ]);
    }

    public function removeRole(UserRoleRequest $request)
    {
        $user = $this->userRoleService->removeRole(
            $request->validated()['user_id'],
            $request->validated()['role_id']
        );

        return response()->json([
            'message' => 'Role telah ditambahkan',
            'data' => $user,
        ]);
    }

    public function listUserRoles(int $userId)
    {
        try {
            $roles = $this->userRoleService->listUserRoles($userId);

            return response()->json([
                'user_id' => $userId,
                'roles' => $roles,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'user tidak ditemukan',
            ], 404);
        }
    }
}
