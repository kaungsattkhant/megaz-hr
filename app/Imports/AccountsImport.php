<?php

namespace App\Imports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\ToModel;

class AccountsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       $account= Account::create([
        'account_code'=>$row[0],
        'name'=>$row[1],
        'sub_account_id'=>(int)$row[2],
       ]);
       return $account;
    }
}
