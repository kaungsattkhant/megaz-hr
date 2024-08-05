<?php

namespace App\Repositories\Task;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getTasksOfRolesFromArea($areaId);

    public function updateTaskStatus(array $data, int $id);

    public function listAllData(Request $request);

    public function createData(array $data);

    public function updateData(array $data, int $id);

    public function deleteData(int $id);

    public function getTasksByStaff(int $id);

    public function doubleCheckTasks(int $id, string $status);

    public function taskReport($request);


    // custom task
    public function customTaskCreate(Request $request);

    public function  listCustomTasks(Request $request);

    public function customTaskUpdate(Request $request,int $id);

    public function taskCustomDetail(int $id);

}
