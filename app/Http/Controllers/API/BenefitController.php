<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Benefit\BenefitInterface;
use App\Http\Requests\Admin\BenefitCreateRequest;
use App\Http\Requests\Admin\BenefitRequestStatusUpdateCheck;
use App\Http\Requests\Mobile\StaffBenefitRequestCreate;

class BenefitController extends Controller
{
    //
    private $benefitRepo;
    public function __construct(BenefitInterface $benefit)
    {
        $this->benefitRepo = $benefit;
    }

    public function index(Request $request)
    {
        $data = $this->benefitRepo->list($request->all());
        \ResponseData($data);
    }

    public function updateOrCreateBenefit(BenefitCreateRequest $request){
        $data = $this->benefitRepo->updateOrCreateBenefit($request->all());
        \ResponseData($data);
    }

    public function detailBenefit($benefitId)
    {
        $data = $this->benefitRepo->detailBenefit($benefitId);
        \ResponseData($data);
    }

    public function requestBenefit(Request $request)
    {
        $data = $this->benefitRepo->requestBenefit($request->all());
        \ResponseData($data);
    }

    public function updateStatusBenefitRequest(BenefitRequestStatusUpdateCheck $request)
    {
        $data = $this->benefitRepo->updateStatusBenefitRequest($request->all());
        \ResponseData($data);
    }

    public function getBenefitByType($type){
        $data = $this->benefitRepo->getBenefitByType($type);
        \ResponseData($data);
    }

    public function createBenefitRequest(StaffBenefitRequestCreate $request){
        $data = $this->benefitRepo->createBenefitRequest($request->all());
        \ResponseData($data);
    }

    public function listBenefitRequest(Request $request){
        $data = $this->benefitRepo->listBenefitRequest($request->all());
        \ResponseData($data);
    }
}
