<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\HandoverStaffList;
use App\Http\Resources\HandOverDetailResource;
use App\Http\Resources\StaffTimeShiftResource;
use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepositoryInterface;

class StaffEquipmentHandoverController extends Controller
{
    private StaffEquipmentHandoverRepositoryInterface $staffEquipmentHandoverRepository;
    public function __construct(StaffEquipmentHandoverRepositoryInterface $staffEquipmentHandoverRepository)
    {
        $this->staffEquipmentHandoverRepository = $staffEquipmentHandoverRepository;
    }

    public function getHandoverStaffs()
    {
        $handoverStaffs = $this->staffEquipmentHandoverRepository->getHandoverStaffs();
        ResponseData(HandoverStaffList::collection($handoverStaffs));
    }

    public function getStaffTimeshift($staffId)
    {
        $staffTimeshift = $this->staffEquipmentHandoverRepository->getStaffTimeshift($staffId);
        ResponseData(StaffTimeShiftResource::collection($staffTimeshift));
    }

    public function createStaffEquipmentHandover(Request $request)
    {
        // $request->validate([
        //     'from_staff_id' => 'required|exists:staff,id',
        //     'to_staff_id' => 'required|exists:staff,id|different:from_staff_id',
        //     'handover_items' => 'required|json',
        //     'notes' => 'nullable|string'
        // ]);
        $staffEquipmentHandover = $this->staffEquipmentHandoverRepository->createStaffEquipmentHandover($request->all());
        ResponseData($staffEquipmentHandover);
    }

    public function confirmHandover($id, Request $request)
    {
        $staffEquipmentHandover = $this->staffEquipmentHandoverRepository->confirmHandover($id, $request->all());
        ResponseData($staffEquipmentHandover);
    }

    public function cancelHandover($id, Request $request)
    {
        $staffEquipmentHandover = $this->staffEquipmentHandoverRepository->cancelHandover($id, $request->all());
        ResponseData($staffEquipmentHandover);
    }

    public function getStaffEquipmentHandoverById($id)
    {
        $staffEquipmentHandover = $this->staffEquipmentHandoverRepository->getStaffEquipmentHandoverById($id);
        ResponseData(HandOverDetailResource::make($staffEquipmentHandover));
    }

    public function getLostItems()
    {
        $lostItems = $this->staffEquipmentHandoverRepository->getLostItems();
        ResponseData($lostItems);
    }
}
