<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\UsedDefectedItem\UsedDefectedITemRepositoryInterface;
use Illuminate\Http\Request;

class UsedDefectedAPIController extends Controller
{
    //
    protected $usedDefectRepo;
    public function __construct(UsedDefectedITemRepositoryInterface $usedDefectRepo)
    {
        $this->usedDefectRepo = $usedDefectRepo;
    }

    public function lisltUsedDefectedItem(Request $request)
    {
        $usedDefectItems = $this->usedDefectRepo->listUsedDefectList($request);
        ResponseData($usedDefectItems);
    }

    public function createUsedDefected(Request $request)
    {
        $usedDefect = $this->usedDefectRepo->createData($request->all());
        ResponseData($usedDefect);
    }

    public function usedDefectConfirm(int $id)
    {
        // dd($id);
        $usedDefect = $this->usedDefectRepo->confirmUsedDefect($id);
    }
}
