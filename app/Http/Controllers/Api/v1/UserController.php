<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{

    use HasApiTokens, HasFactory, Notifiable;

    public function __construct(private UserService $service)
    {
    }

    public function store(StoreUserRequest $request)
    {

        $dto = UserDTO::fromRequest($request);
        $user = $this->service->store($dto);

        return response()->json(['message' => 'User created successfully', 'data' => $user], 201);

    }


    public function update(UpdateUserRequest $request, $id)
    {

        $dto = UserDTO::fromRequest($request);
        $user = $this->service->update($dto, $id);

        return response()->json(['message' => 'User updated successfully', 'data' => $user], 201);
    }


    public function getUserById(int $id)
    {

        $user = $this->service->getUserById($id);

        return response()->json(['message' => 'User found successfully', 'data' => $user], 201);
    }
}
