<?php

namespace App\Repositories\Entity;

use Illuminate\Http\Request;

interface EntityRepositoryInterface
{
    public function listAllData(Request $request, string $entityType);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function deleteData(int $id);

    public function roomsWithInvoice(array $data);

    public function inactiveRoomsList(Request $request);
}
