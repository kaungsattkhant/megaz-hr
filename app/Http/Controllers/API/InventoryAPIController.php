<?php

namespace App\Http\Controllers\API;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaEquipmentResource;
use App\Http\Requests\Inventory\InventoryItemRequest;
use App\Http\Requests\Inventory\InventoryCreateRequest;
use App\Http\Requests\Inventory\InventoryUpdateRequest;
use App\Repositories\Inventory\InventoryRepositoryInterface;

class InventoryAPIController extends Controller
{
    //
    protected $inventoryRepo;
    public function __construct(InventoryRepositoryInterface $inventoryRepo)
    {
        $this->inventoryRepo = $inventoryRepo;
    }

    public function getallInventories(Request $request)
    {
        $inventories = $this->inventoryRepo->getallInventories($request);
        ResponseData($inventories);
    }

    public function getInventory(Request $request)
    {
        $inventories = $this->inventoryRepo->getInventory($request);
        ResponseData($inventories);
    }
    public function getInventoryData(Request $request)
    {
        $inventories = $this->inventoryRepo->listAllData($request);
        ResponseData($inventories);
    }

    public function createInventory(InventoryCreateRequest $request)
    {
        $inventory = $this->inventoryRepo->createData($request);
        ResponseData($inventory);
    }

    public function updateInventory(InventoryUpdateRequest $request, $id)
    {
        $inventory = $this->inventoryRepo->updateData($request->all(), $id);
        ResponseData($inventory);
    }

    public function detail(Inventory $inventory)
    {
        $inventory = $this->inventoryRepo->detail($inventory);
        ResponseData($inventory);
    }

    public function deleteInventory($id)
    {
        $inventory = $this->inventoryRepo->deleteData($id);
        if ($inventory == true) {
            ResponseMessage('Inventory deleted');
        } else {
            ResponseMessage('Inventory not found or some error occur');
        }
    }

    public function getInventoryLedgers(Request $request, int $inventoryId)
    {
        $ledgers = $this->inventoryRepo->getInventoryLedgers($inventoryId, $request);

        ResponseData($ledgers);
    }

    public function inventoryList()
    {
        $data = $this->inventoryRepo->inventoryList();
        ResponseData($data);
    }

    public function getInventoryLedgerList(Request $request)
    {
        $data = $this->inventoryRepo->getInventoryLedgerList($request);
        ResponseData($data);
    }

    public function createInventoryItem(InventoryItemRequest $request)
    {
        $inventory = $this->inventoryRepo->createInventoryItem($request->validated());
        ResponseData($inventory);
    }

    public function getInventoryItemsByStaff($areaId)
    {
        $inventoryItems = $this->inventoryRepo->getInventoryItemsByStaff($areaId);
        ResponseData(AreaEquipmentResource::collection($inventoryItems));
    }
}
