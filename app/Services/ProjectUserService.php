<?php

namespace App\Services;

use App\Repositories\ProjectUser\ProjectUserRepository;

class ProjectUserService
{
    protected $projectUserRepository;

    public function __construct(ProjectUserRepository $projectUserRepository)
    {
        $this->projectUserRepository = $projectUserRepository;
    }

    public function addMember($projectId, $userId)
    {
        return $this->projectUserRepository->addMember($projectId, $userId);
    }

    public function removeMember($projectId, $userId)
    {
        return $this->projectUserRepository->removeMember($projectId, $userId);
    }

    public function getMembers($projectId)
    {
        return $this->projectUserRepository->getMembers($projectId);
    }

    public function isMember($projectId, $userId)
    {
        return $this->projectUserRepository->isMember($projectId, $userId);
    }

}
