<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    private $projectService;

    public function __construct(ProjectService $projectService)
    {

        $this->projectService = $projectService;
    }

    public function create(Request $request)
    {
        try {

            $project = $this->projectService->create($request);

            return responseHandler([

                'message' => 'Project created successfully',
                'status'  => 200,
                'data'    => $project

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error creating the project',
                'status'  => 500,
                'data'    => $e->getMessage()

            ]);
        }
    }


    public function getAllByUser(Request $request)
    {
        try {

            $project = $this->projectService->getAllByUser();

            return responseHandler([

                'message' => 'Projects found successfully',
                'status'  => 200,
                'data'    => $project

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'An error occurred when searching for user projects',
                'status'  => 500,
                'data'    => $e->getMessage()

            ]);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $project = $this->projectService->update($request, $id);

            return responseHandler([

                'message' => 'Project updated successfully',
                'status'  => 200,
                'data'    => $project

            ]);
        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'There was an error updating the project',
                'status'  => 500,
                'data'    => $e->getMessage()

            ]);
        }
    }
}
