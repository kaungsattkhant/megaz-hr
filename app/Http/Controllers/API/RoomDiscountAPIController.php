<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\RoomDiscount\RoomDiscountRepositoryInterface;
use Illuminate\Http\Request;

class RoomDiscountAPIController extends Controller
{
    //
    protected $roomDiscountRepo;

    public function __construct(RoomDiscountRepositoryInterface $roomDiscountRepo)
    {
        $this->roomDiscountRepo = $roomDiscountRepo;
    }

    public function getRoomDiscount(Request $request)
    {
        $roomDiscount = $this->roomDiscountRepo->listAllData($request);
    }

    public function createRoomDiscount(Request $request)
    {
        $roomDiscount = $this->roomDiscountRepo->createData($request->all());
    }

    public function editRoomDiscount(int $id, Request $request)
    {
        $roomDiscount = $this->roomDiscountRepo->editData($id, $request->all());
    }

    public function deleteRoomDiscount(int $id)
    {
        $roomDiscount = $this->roomDiscountRepo->deleteData($id);
    }
}
