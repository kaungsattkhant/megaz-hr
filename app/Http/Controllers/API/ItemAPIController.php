<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\UomConversion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemRequest;
use App\Http\Requests\Item\ItemImportRequest;
use App\Repositories\Item\ItemRepositoryInterface;

class ItemAPIController extends Controller
{
    //
    protected $itemRepo;
    public function __construct(ItemRepositoryInterface $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    public function getItemData(Request $request)
    {
        $items = $this->itemRepo->listAllData($request);
        ResponseData($items);
    }
    public function getAllItem(Request $request)
    {
        $items = $this->itemRepo->getAllItem($request);
        ResponseData($items);
    }
    public function detail($id)
    {
        $items = $this->itemRepo->detail($id);
        ResponseData($items);
    }
    public function createItem(ItemRequest $request)
    {
        $item = $this->itemRepo->createData($request->all());
        ResponseData($item);
    }

    public function updateItem(Request $request, int $id)
    {
        $item = $this->itemRepo->updateData($request->all(), $id);
        ResponseData($item);
    }

    public function deleteItem(int $id)
    {
        $item = $this->itemRepo->deleteData($id);
        if ($item == true) {
            ResponseMessage("Item deleted");
        } else {
            ResponseMessage('Item not found or some error occur');
        }
    }

    public function addItemPrice(Request $request)
    {
        $item = $this->itemRepo->addPriceItem($request);
        ResponseData($item);
    }

    public function getItemPriceListByItem($item_id)
    {
        $item = $this->itemRepo->getItemPriceListByItem($item_id);
        ResponseData($item);
    }

    public function getItemType()
    {
        $item = $this->itemRepo->getItemType();
        ResponseData($item);
    }
    public function supplierByItem($itemId)
    {
        $item = $this->itemRepo->supplierByItem($itemId);
        ResponseData($item);
    }

    public function brandBySupplier(Request $request)
    {

        $brand = $this->itemRepo->brandBySupplier($request);
        ResponseData($brand);
    }
    public function itemImport(ItemImportRequest $request)
    {
        $item = $this->itemRepo->itemImport($request);
        return $item;
    }
    public function brandlistOfSupplierByItem($itemId)
    {
        $item = $this->itemRepo->brandlistOfSupplierByItem($itemId);
        return $item;
    }

    public function createCategory(Request $request)
    {
        $item = $this->itemRepo->createCategory($request);
        return $item;
    }

    public function createItemType(Request $request)
    {
        $item = $this->itemRepo->createItemType($request);
        return $item;
    }

    public function importItemType(Request $request)
    {
        $item = $this->itemRepo->importItemType($request);
        return $item;
    }

    public function importCategory(Request $request)
    {
        $item = $this->itemRepo->importCategory($request);
        return $item;
    }

    public function importUom(Request $request)
    {
        $item = $this->itemRepo->importUom($request);
        return $item;
    }

    public function itemPriceImport(Request $request){
        $item = $this->itemRepo->importItemPrice($request);
        return $item;
    }
    public function equipmentItem(Request $request)
    {
        $item = $this->itemRepo->equipmentItem($request);
        ResponseData($item);
    }
}
