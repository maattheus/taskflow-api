<?php
namespace App\Repositories\ProjectUser;

use App\Models\Project;

class ProjectUserRepository implements ProjectUserInterface
{
    public function addMember($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->members()->attach($userId); // adiciona usuário ao projeto
        return $project;
    }

    public function removeMember($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->members()->detach($userId); // remove usuário do projeto
        return $project;
    }

    public function getMembers($projectId)
    {
        $project = Project::findOrFail($projectId);
        return $project->members; // retorna todos os membros do projeto
    }

    public function isMember($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        return $project->members()->where('user_id', $userId)->exists();
    }
}