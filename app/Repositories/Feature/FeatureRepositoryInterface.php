<?php

namespace App\Repositories\Feature;

interface FeatureRepositoryInterface
{
    public function listAllData();
    public function gtFeatureByDepartment($departmentId);

    public function getFeatureByModule();
}
