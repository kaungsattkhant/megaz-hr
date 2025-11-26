<?php

namespace App\Repositories\Bank;

interface BankRepositoryInterface
{
  public function getAllBanks();
  public function createBank(array $data);
}
