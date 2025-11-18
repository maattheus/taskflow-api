<?php

namespace App\Repositories\User;

use App\DTOs\UserDTO;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function create(UserDTO $dto)
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
            'role' => $dto->role ?? 'member',
        ]);
    }

    public function getUserById(int $id)
    {
        return User::findOrFail($id);
    }

    public function update(UserDTO $dto, int $id)
    {
        $user = User::findOrFail($id);

        $user->fill([
            'name' => $dto->name ?? $user->name,
            'email' => $dto->email ?? $user->email,
            'password' => $dto->password ? bcrypt($dto->password) : $user->password,
            'role' => $dto->role ?? $user->role,
        ]);

        $user->save();

        return $user;
    }
}
