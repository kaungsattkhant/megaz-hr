<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KpiSnapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiSnapshotController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = KpiSnapshot::orderBy('id', 'asc')->get();
        \ResponseData($data);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $kpiSnapshot = KpiSnapshot::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'name' => $data['name'],
                ]
            );
            DB::commit();
            \ResponseData($kpiSnapshot);
        } catch (\Throwable $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
}
