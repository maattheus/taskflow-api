<?php

namespace App\Repositories\Project;


interface ProjectInterface
{

    public function create($params);

    public function getAllByUser();

    public function getProjectById($id);

    public function update($data, $id);

}