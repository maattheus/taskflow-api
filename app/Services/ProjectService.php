<?php

namespace App\Services;

use App\DTOs\ProjectDTO;
use App\Repositories\Project\ProjectInterface;
use Illuminate\Support\Facades\Log;

class ProjectService
{

    public function __construct(private ProjectInterface $repository)
    {
    }

    public function create(ProjectDTO $data)
    {

        try {

            return $this->repository->create($data);

        } catch (\Throwable $e) {
            Log::channel('project')->error('Error creating a project.', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

    }

    public function update(ProjectDTO $data, $id)
    {
        try {

            return $this->repository->update($data, $id);
        } catch (\Throwable $e) {
            Log::channel('project')->error('Error updating a project.', [
                'message' => $e->getMessage(),
                'project_id' => $id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }


    }


    public function getByUser()
    {

        try {

            return $this->repository->getByUser();

        } catch (\Throwable $e) {
            Log::channel('project')->error('Error retrieving projects for the user.', [
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }

    }

}