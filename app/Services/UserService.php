<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(array $fields, $perPage = null)
    {
        return $this->userRepository->getAll($fields, $perPage);
    }

    public function getById(int $id, array $fields)
    {
        return $this->userRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        return $this->userRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        return $this->userRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->userRepository->delete($id);
    }
}
