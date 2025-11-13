<?php

namespace App\Http\Controllers\Api\v1;

use App\DTOs\BoardDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBoardRequest;
use App\Http\Requests\UpdateBoardRequest;
use App\Services\BoardService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function __construct(private BoardService $service)
    {
    }

    public function getByProject(Request $request)
    {
        $boards = $this->service->getByProject($request->id);

        return response()->json(['message' => 'Board retrieved successfully', 'data' => $boards], 200);

    }

    public function store(StoreBoardRequest $request)
    {
        $dto = BoardDTO::fromRequest($request);
        $board = $this->service->create($dto);

        return response()->json([
            'message' => 'Board created successfully',
            'data' => $board
        ], 201);
    }


    public function update(UpdateBoardRequest $request, $id)
    {

        $dto = BoardDTO::fromRequest($request);
        $board = $this->service->update($dto, $id);

        return response()->json(['message' => 'Board updated successfully', 'data' => $board], 200);


    }
}
