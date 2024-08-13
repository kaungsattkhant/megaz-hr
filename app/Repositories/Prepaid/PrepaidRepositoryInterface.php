<?php

namespace App\Repositories\Prepaid;

use Illuminate\Http\Request;

interface PrepaidRepositoryInterface
{
    public function createPrepaid(Request $request);
}
