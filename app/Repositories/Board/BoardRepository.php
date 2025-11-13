<?php

namespace App\Repositories\Board;

use App\DTOs\BoardDTO;
use App\Models\Board;

class BoardRepository implements BoardInterface
{
    public function getByProject($id)
    {
        return Board::where('project_id', $id)
            ->get();
    }

    public function createFromDto(BoardDTO $data)
    {

        $board = new Board();
        $board->fill([
            'name' => $data->name,
            'project_id' => $data->project_id,
        ]);

        $board->save();
        return $board;

    }

    public function updateFromDto(BoardDTO $data, int $id)
    {

        $board = Board::findOrFail($id);
        $board->fill([
            'name' => $data->name ?? $board->name
        ]);

        return $board;

    }

    public function findById(int $id): ?Board
    {
        return Board::find($id);
    }


}
