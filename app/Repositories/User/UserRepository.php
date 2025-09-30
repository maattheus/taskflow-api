<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserInterface
{

    public function create($data)
    {

        return User::create($data);
  
    }


    public function getUserById($id)
    {

        return User::findOrFail($id); 

    }


    public function update($data, $id)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

}