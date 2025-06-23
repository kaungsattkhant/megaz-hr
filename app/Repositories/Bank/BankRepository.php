<?php

namespace App\Repositories\Bank;

use App\Models\Bank;
use App\Repositories\Bank\BankRepositoryInterface;


class BankRepository implements BankRepositoryInterface
{
  public function getAllBanks()
  {
    return Bank::all();
  }
  public function createBank(array $data)
  {
    return Bank::create($data);
  }
}
