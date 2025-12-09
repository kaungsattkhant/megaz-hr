<?php

namespace App\Http\Controllers\API;

use App\Models\Advance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvancePaymentCreateRequest;
use App\Repositories\Advance\AdvanceInterface;
use App\Http\Requests\Admin\AdvanceStoreRequest;

class AdvanceController extends Controller
{
    //
    private $advanceRepo;
    public function __construct(AdvanceInterface $advance)
    {
       $this->advanceRepo=$advance;
    }

    public function index(Request $request){
        $data = $this->advanceRepo->list($request->all());
        \ResponseMessage($data);
    }
    public function create(AdvanceStoreRequest $request){
        $data=$this->advanceRepo->create($request->all());
        \ResponseMessage($data);
    }

    public function getAdvancePaymentDetailByAdvance($advanceId){
        $data = $this->advanceRepo->getAdvancePaymentDetailByAdvance($advanceId);
        \ResponseMessage($data);
    }

    public function createAdvancePayment(AdvancePaymentCreateRequest $request){
        $data = $this->advanceRepo->createAdvancePayment($request->all());
        \ResponseMessage($data);
    }

    public function getStaffAdvanceHistory(Request $request){
        $data = $this->advanceRepo->getStaffAdvanceHistory($request->all());
        \ResponseMessage($data);
    }
}
