<?php

namespace App\Repositories\Project;

use App\DTOs\ProjectDTO;
use App\Models\Project;
use App\Repositories\Project\ProjectInterface;
use Illuminate\Support\Facades\Auth;

class ProjectRepository implements ProjectInterface
{

    public function create(ProjectDTO $dto)
    {
        return Project::create([
            'name' => $dto->name,
            'description' => $dto->description,
            'user_id' => Auth::id(),
        ]);
    }


    public function getByUser()
    {

        return Project::where('user_id', Auth::id())->get();

    }


    public function getProjectById($id)
    {

        return Project::findOrFail($id);

    }

    public function update(ProjectDTO $dto, int $id)
    {
        $project = Project::findOrFail($id);

        $updateData = array_filter([
            'name' => $dto->name,
            'description' => $dto->description,
        ], fn($value) => !is_null($value));

        $project->update($updateData);

        return $project->fresh();
    }

}