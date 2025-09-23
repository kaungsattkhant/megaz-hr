<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\StaffEquipmentHandover\StaffEquipmentHandoverRepositoryInterface;
use Illuminate\Http\Request;

class StaffEquipmentHandoverController extends Controller
{
    private StaffEquipmentHandoverRepositoryInterface $staffEquipmentHandoverRepository;
    public function __construct(StaffEquipmentHandoverRepositoryInterface $staffEquipmentHandoverRepository)
    {
        $this->staffEquipmentHandoverRepository = $staffEquipmentHandoverRepository;
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
}
