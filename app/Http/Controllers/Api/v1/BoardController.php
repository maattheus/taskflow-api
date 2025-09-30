<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\BoardService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    protected $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    public function getAllByProject(Request $request)
    {
        try {

            $boards = $this->boardService->getAllByProject($request->id);

            return responseHandler([

                'data' => $boards,
                'message' => 'Boards fetched successfully',
                'status' => 200

            ]);

        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'Error fetching boards',
                'status' => 500,
                'error' => $e->getMessage()

            ]);

        }

    }

    public function create(Request $request)
    {
        try {

            $board = $this->boardService->create($request);

            return responseHandler([

                'data' => $board,
                'message' => 'Board created successfully',
                'status' => 201

            ]);

        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'Error creating board',
                'status' => 500,
                'error' => $e->getMessage()

            ]);

        }

    }

    public function update(Request $request, $id)
    {
        
        try {

            $board = $this->boardService->update($request, $id);

            return responseHandler([

                'data' => $board,
                'message' => 'Board updated successfully',
                'status' => 200

            ]);

        } catch (\Exception $e) {

            return responseHandler([

                'message' => 'Error updating board',
                'status' => 500,
                'error' => $e->getMessage()

            ]);

        }

    }

}
