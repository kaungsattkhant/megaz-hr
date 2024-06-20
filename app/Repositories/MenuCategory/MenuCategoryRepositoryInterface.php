<?php

namespace App\Repositories\MenuCategory;

interface MenuCategoryRepositoryInterface
{
    public function listAllData();

    public function createData(array $data);

    public function editData(int $id, array $data);

    public function deleteData(int $id);

    // user app
    public function listAllDataUser();
}
