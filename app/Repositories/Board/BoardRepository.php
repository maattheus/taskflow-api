<?php

namespace App\Repositories\Board;

use App\Models\Board;

class BoardRepository implements BoardInterface
{
    public function getAllByProject($id)
    {
        return Board::where('project_id', $id)
                        ->get( );
    }

    public function create($data)
    {   

        return Board::create($data->all());

    }   

    public function update($data, $id)
    {   

        $board = Board::find($id);
        $board->update($data->all());
        return $board;

    }
}
