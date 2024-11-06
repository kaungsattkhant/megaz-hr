<?php

namespace App\Repositories\Accessory;


use App\Models\Menu;
use App\Models\Accessory;
use App\Models\AccessoryItem;
use App\Models\AccessoryPrice;
use App\Models\InvoiceAccessory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccessoryRepository implements AccessoryInterface
{
    public function list($request){
        // $validateDate = $request->date ?? CurrentDate();
        if ($request->per_page || $request->page) {
            $accessory_category_id = $request->accessory_category_id;

            return Accessory::with(['accessory_category', 'accessory_price', 'accessory_items.item'])
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($accessory_category_id, function ($query) use ($accessory_category_id) {
                    $query->where('accessory_category_id', $accessory_category_id);
                })
                ->paginate(config('common.list_count'));
        } else {
            $acessories = Accessory::with(['accessory_category', 'accessory_price', 'accessory_items'])->where('is_active', 1)->get();
            return $acessories;
        }
    }

    public function store($request){
        $data=$request->all();
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images/accessory_images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);
            $items=json_decode($request->accessory_items);
            $data['created_by']=UserData()->id;
            $accessory = Accessory::updateOrCreate(
                ['id' => $data['id']],
                $data);
            if (!isset($request->id)) {
                $price=$this->addAccessoryPrice($accessory->id, $data['price']);
            }
            foreach ($items as $item) {
                if (isset($item->id) && $item->id !== null) {
                    $item_data['id'] = $item->id;
                } else {
                    $item_data['id'] = null;
                }
                $item_data['accessory_id']=$accessory->id;
                $item_data['item_id']=$item->item_id;
                $item_data['uom_id']=$item->uom_id;
                $item_data['price']=$item->price;
                $item_data['quantity']=$item->quantity;
                $accessory_item = $accessory->accessory_items()->updateOrCreate(['id' => $item_data['id']], $item_data);
            }
            DB::commit();
            return $accessory;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function deleteAccessoryItem($id)
    {
        $accessory_item = AccessoryItem::find($id);
        if ($accessory_item) {
            $accessory_item->delete();
            ResponseMessage("Delete successfully", 200);
        } else {
            ResponseMessage("Data isn't found ", 404);
        }
    }

    public function addAccessoryPrice($accessoryId,$price)
    {
        $accessory = Accessory::find($accessoryId);
        if ($accessory) {
            $accessoryPrice = AccessoryPrice::create([
                "accessory_id" => $accessory->id,
                "price" => $price
            ]);
            return $accessoryPrice;
        }

        return null;
    }

    public function detail($accessory){
        $accessory->load('accessory_price');
        $accessory->load('accessory_category');
        $accessory->load('accessory_items.uom', 'accessory_items.item');
        return $accessory;
    }

    public function getAccessoryByCategory($accessory_category_id){
        $accessories=Accessory::with(['accessory_price'])->where('accessory_category_id',$accessory_category_id)
        ->where('is_active',1)
        ->get();
        return $accessories;
    }

    public function createInvoiceAccessory($request){
        // dd($request->all());
        $data=$request->all();
        // dd($data);
        DB::beginTransaction();
        try {
            $invoiceAccessory=InvoiceAccessory::create($data);
            DB::commit();
            return $invoiceAccessory;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}