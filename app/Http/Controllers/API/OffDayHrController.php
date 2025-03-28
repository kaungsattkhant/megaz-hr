<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}
