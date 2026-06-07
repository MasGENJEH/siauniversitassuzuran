<?php

namespace App\Services;

use App\Repositories\UserRoleRepository;

class UserRoleService
{
    private UserRoleRepository $userRoleRepository;

    public function __construct(UserRoleRepository $userRoleRepository)
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    public function assignRole(int $userId, int $roleId)
    {
        return $this->assignRole($userId, $roleId);
    }

    public function removeRole(int $userId, int $roleId)
    {
        return $this->removeRole($userId, $roleId);
    }

    public function listUserRoles(int $userId)
    {
        return $this->listUserRoles($userId);
    }
}
