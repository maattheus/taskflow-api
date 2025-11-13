<?php

namespace App\Repositories\Project;

use App\DTOs\ProjectDTO;


interface ProjectInterface
{

    public function create(ProjectDTO $data);

    public function getByUser();

    public function getProjectById(int $id);

    public function update(ProjectDTO $data, int $id);

}