<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll(array $fields)
    {
        return User::select($fields)->with('roles')->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return User::select($fields)->with('roles')->findOrFail($id);
    }

    public function create(array $data)
    {
        $user = User::create($data);
        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        return $user;
    }

    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        
        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function delete(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
