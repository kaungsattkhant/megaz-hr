<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffEquipmentAssignResource;
use App\Repositories\AssetItemEquipmentAssign\AssetItemEquipmentAssignRepositoryInterface;

class AssetItemEquipmentAssignController extends Controller
{
    private AssetItemEquipmentAssignRepositoryInterface $assetItemEquipmentAssignRepo;

    public function __construct(AssetItemEquipmentAssignRepositoryInterface $assetItemEquipmentAssignRepo)
    {
        $this->assetItemEquipmentAssignRepo = $assetItemEquipmentAssignRepo;
    }
    public function getAssetAssigns(Request $request)
    {
        $assetAssigns = $this->assetItemEquipmentAssignRepo->getAssetAssigns($request);
        ResponseData($assetAssigns);
    }

    public function createAssetAssign(Request $request)
    {
        $assetAssign = $this->assetItemEquipmentAssignRepo->createAssetAssign($request->all());
        ResponseData($assetAssign, 201);
    }

    public function createEquipmentAssign(Request $request)
    {
        $equipmentAssign = $this->assetItemEquipmentAssignRepo->createEquipmentAssign($request->all());
        ResponseData($equipmentAssign, 201);
    }

    public function getEquipmentAssigns()
    {
        $equipmentAssigns = $this->assetItemEquipmentAssignRepo->getEquipmentAssigns();
        ResponseData($equipmentAssigns);
    }

    public function getEquipmentAssignsByStaffId(Request $request){
        
        $equipmentAssigns = $this->assetItemEquipmentAssignRepo->getEquipmentAssignsByStaffId($request);
        ResponseData($equipmentAssigns);
        // ResponseData(StaffEquipmentAssignResource::collection($equipmentAssigns));
    }
}
