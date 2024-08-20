<?php

namespace App\Repositories\Journal;

use Illuminate\Http\Request;

interface JournalRepositoryInterface
{
    public function listAllData(Request $request);
    public function createData(Request $request);
}
