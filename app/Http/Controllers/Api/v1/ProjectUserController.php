<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\ProjectUserService;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    private $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    public function addMember(Request $request, $projectId)
    {
        try {
            $userId = $request->user_id;
            $project = $this->projectUserService->addMember($projectId, $userId);

            return responseHandler([
                'message' => 'User added to project successfully',
                'status' => 200,
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error adding user to project',
                'status' => 500,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function removeMember(Request $request, $projectId)
    {
        try {
            $userId = $request->user_id;
            $project = $this->projectUserService->removeMember($projectId, $userId);

            return responseHandler([
                'message' => 'User removed from project successfully',
                'status' => 200,
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error removing user from project',
                'status' => 500,
                'data' => $e->getMessage()
            ]);
        }
    }

    public function getMembers($projectId)
    {
        try {

            $members = $this->projectUserService->getMembers($projectId);

            return responseHandler([
                'message' => 'Project members fetched successfully',
                'status' => 200,
                'data' => $members
            ]);
        } catch (\Exception $e) {
            return responseHandler([
                'message' => 'Error fetching project members',
                'status' => 500,
                'data' => $e->getMessage()
            ]);
        }
    }
}
