<?php

namespace App\Services;

use App\Repositories\User\UserRepository;

class UserService
{

    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {

        $this->userRepo = $userRepo;

    }

    public function create($data)
    {

        return $this->userRepo->create($data);

    }
    

    public function getUserById($id)
    {

        return $this->userRepo->getUserById($id);

    }


    public function update($data, $id)
    {

        return $this->userRepo->update($data, $id);

    }

}