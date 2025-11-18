<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\ProjectDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function __construct(private ProjectService $service)
    {
    }

    public function store(StoreProjectRequest $request)
    {

        $dto = ProjectDTO::fromRequest($request);
        $project = $this->service->create($dto);

        return response()->json(['message' => 'Project created successfully', 'data' => $project], 201);

    }


    public function getByUser(Request $request)
    {

        $project = $this->service->getByUser();

        return response()->json(['message' => 'Projects found successfully', 'data' => $project], 200);


    }

    public function update(UpdateProjectRequest $request, $id)
    {

        $dto = ProjectDTO::fromRequest($request);
        $project = $this->service->update($dto, $id);

        return response()->json(['message' => 'Project updated successfully', 'data' => $project], 200);

    }
}
