<?php

namespace App\Repositories\Task;

interface TaskRepoitoryInterface
{
    public function getTasksOfRolesFromArea($areaId, $roles);
}
