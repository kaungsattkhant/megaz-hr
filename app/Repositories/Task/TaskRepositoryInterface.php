<?php

namespace App\Repositories\Task;

interface TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea($areaId, $roles);

    public function listAllData();

    public function createData(array $data);

    public function updateData(array $data, string $id);

    public function deleteData($id);
}
