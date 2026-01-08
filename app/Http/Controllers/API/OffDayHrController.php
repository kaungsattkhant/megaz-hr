<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OffDaySettingToggleRequest;
use App\Models\OffDaySetting;
use App\Repositories\OffDay\OffDayRepositoryInterface;
use Illuminate\Http\Request;

class OffDayHrController extends Controller
{
    private OffDayRepositoryInterface $offDayRepository;

    public function __construct(OffDayRepositoryInterface $offDayRepository)
    {
        $this->offDayRepository = $offDayRepository;
    }

    public function getOffDays()
    {
        $data =  $this->offDayRepository->getOffDays();
        ResponseData($data);
    }
    public function createOffDay(Request $request)
    {
        $data =  $this->offDayRepository->createOffDay($request->all());
        ResponseData($data);
    }


    public function deleteOffDay($dayInOffDayId)
    {
        $data =  $this->offDayRepository->deleteOffDay($dayInOffDayId);
        ResponseData($data);
    }

    public function createPublicHoliday(Request $request)
    {
        $data = $this->offDayRepository->createPublicHoliday($request->all());
        ResponseData($data);
    }
    public function toggleOffDaySetting(OffDaySettingToggleRequest $request){
        $data = $this->offDayRepository->toggleOffDaySetting($request->all());
        ResponseData($data);
    }

    public function getOffDaySetting(){
        $data=OffDaySetting::get();
        \ResponseData($data);
    }

    public function getOffDayRequests()
    {
        $data = $this->offDayRepository->getOffDayRequests();
        ResponseData($data);
    }

    public function updateOffDayRequestStatus($id, Request $request)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $this->offDayRepository->updateOffDayRequestStatus($id, $request);
        ResponseMessage("OK");
    }
}
