<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvanceStoreRequest;
use App\Repositories\Advance\AdvanceInterface;
use Illuminate\Http\Request;

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
}
