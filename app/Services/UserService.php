<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(private UserInterface $repository)
    {
    }

    public function store(UserDTO $data)
    {
        try {
            return $this->repository->create($data);
        } catch (\Throwable $e) {
            Log::channel('user')->error('Error creating user.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'user_email' => $data->email ?? null,
                'created_by' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function getUserById(int $id)
    {
        try {
            return $this->repository->getUserById($id);
        } catch (\Throwable $e) {
            Log::channel('user')->error('Error fetching user by ID.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'user_id' => $id,
                'requested_by' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function update(UserDTO $data, int $id)
    {
        try {
            return $this->repository->update($data, $id);
        } catch (\Throwable $e) {
            Log::channel('user')->error('Error updating user.', [
                'trace_id' => Str::uuid()->toString(),
                'message' => $e->getMessage(),
                'user_id' => $id,
                'updated_by' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
