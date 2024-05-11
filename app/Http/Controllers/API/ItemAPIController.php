<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Item\ItemRepositoryInterface;
use Illuminate\Http\Request;

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

    public function createItem(Request $request)
    {
        $item = $this->itemRepo->createData($request->all());
        ResponseData($item);
    }

    public function updateItem(Request $request, int $id)
    {
        $item = $this->itemRepo->updateData($request->all(),$id);
        ResponseData($item);
    }

    public function deleteItem(int $id)
    {
        $item = $this->itemRepo->deleteData($id);
        if($item==true)
        {
            ResponseMessage("Item deleted");
        }else{
            ResponseMessage('Item not found or some error occur');
        }
    }

    public function addItemPrice(Request $request,int $id)
    {
        $item = $this->itemRepo->addPriceItem($request->all(),$id);
        ResponseData($item);
    }
}
