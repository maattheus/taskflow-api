<?php

namespace App\Repositories\User;

use App\DTOs\UserDTO;

interface UserInterface
{

    public function create(UserDTO $data);

    public function getUserById(int $id);

    public function update(UserDTO $data, int $id);

}