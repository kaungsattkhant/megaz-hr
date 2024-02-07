<?php

namespace App\Repositories\Complain;

interface ComplainRepositoryInterface
{
    public function listAllData();

    public function createData(array $data);

    public function updateData(array $data,$id);

    public function deleteData($id);


}
