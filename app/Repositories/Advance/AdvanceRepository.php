<?php

namespace App\Repositories\Advance;

use App\Models\Advance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvanceRepository implements AdvanceInterface
{
    public function create($data)
    {
        DB::beginTransaction(); // start transaction

        try {
            // 1. Create Staff Advance
            $advance= Advance::create($data);
            DB::commit();
           return $advance;
        } catch (\Exception $e) {
            DB::rollBack(); // rollback all queries if any error occurs

            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}