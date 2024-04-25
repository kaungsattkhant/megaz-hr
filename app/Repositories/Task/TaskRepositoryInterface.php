<?php

namespace App\Repositories\Task;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea(int $areaId, array $roleIds);

    public function updateTaskStatus(array $data, int $id);

    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getTasksByStaff(int $id);

    public function doubleCheckTasks(int $id);
}
