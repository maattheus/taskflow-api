<?php

namespace App\Repositories\User;

interface UserInterface
{

    public function create($data);

    public function getUserById($id);

    public function update($data, $id);

}