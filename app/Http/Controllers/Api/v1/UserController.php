<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserController extends Controller
{

    use HasApiTokens, HasFactory, Notifiable;
    private $userService;

    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }

    public function create(CreateUserRequest $request)
    {

        try {

            $data = $request->validated();

            $user = $this->userService->create($data);

            return responseHandler([

                'message' => 'User created successfully',
                'status'  => 200,
                'data'    => $user

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error creating the user',
                'status'  => 500,
                'data'    => $e->getMessage()

            ]);
        }
    }


    public function update(Request $request, $id)
    {

        try {

            $user = $this->userService->update($request->all(), $id);

            return responseHandler([

                'message' => 'User updated successfully',
                'status'  => 200,
                'data'    => $user

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error updating the user',
                'status'  => 500,
                'data'    => $e->getMessage()

            ]);
        }
    }


    public function getUserById($id)
    {

        try {

            $user = $this->userService->getUserById($id);

            return responseHandler([
                'message' => 'User found successfully',
                'status'  => 200,
                'data'    => $user
            ]);
        } catch (\Exception $e) {

            return responseHandler([
                'message' => 'There was an error fetching the user',
                'status'  => 500,
                'data'    => $e->getMessage()
            ]);
        }
    }
}
