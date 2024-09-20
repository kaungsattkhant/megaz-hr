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

    public function taskByRoleId(int $departmentId);


    // custom task
    public function customTaskCreate(Request $request);

    public function  listCustomTasks(Request $request);

    public function customTaskUpdate(Request $request,int $id);

    public function taskCustomDetail(int $id);

    // add image

    public function addTaskImages(int $taskId,Request $request);

    public function getTaskImages(int $task_id);

    public function deleteTaskImage(int $image_id);


}
