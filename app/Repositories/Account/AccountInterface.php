<?php

namespace App\Repositories\Account;

use Illuminate\Http\Request;

interface AccountInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($headAccount);

}