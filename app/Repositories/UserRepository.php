<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getAll(array $fields)
    {
        return User::select($fields)->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return User::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $fakultas = User::findOrFail($id);
        $fakultas->update($data);

        return $fakultas;
    }

    public function delete(int $id)
    {
        $fakultas = User::findOrFail($id);
        $fakultas->delete();
    }
}
