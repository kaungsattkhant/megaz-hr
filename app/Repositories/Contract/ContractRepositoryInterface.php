<?php

namespace App\Repositories\Contract;

interface ContractRepositoryInterface
{
    public function list($request);

    public function updateOrCreate(array $data);

    public function detail($contract);

    public function getContractList();

    public function addStaffToContract(array $data);
}
