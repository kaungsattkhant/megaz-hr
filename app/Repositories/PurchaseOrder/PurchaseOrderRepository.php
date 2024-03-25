<?php

namespace App\Repositories\PurchaseOrder;

use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;
use App\Http\Action\Inventory\StoreInventory;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    private $select = ['po_id', 'total_price', 'created_by', 'manager_check_id', 'manager_check_time', 'financial_check_id', 'financial_check_time', 'is_md_check', 'status', 'created_at', 'updated_at'];
    public function listAllData(Request $request)
    {
        $staff = UserData();
        $purchaseOrders = PurchaseOrder::with(['items.item','createdBy','managerCheckedBy','financialCheckedBy'])
            ->orderBy('id', 'desc')
            ->when($staff->hasRoles('Staff'), function ($q) use ($staff) {
                $q->where('created_by', $staff->id);
            })
            ->when($staff->hasRoles('Manager'), function ($q) {
                $q->whereIn('status', ['manager_checked', 'created']);
            })
            ->when($staff->hasRoles('Financial'), function ($q) {
                $q->whereIn('status', ['manager_checked', 'financial_checked']);
            })
            ->when($staff->hasRoles('MD'), function ($q) {
                $q->whereIn('status', ['md_checked', 'financial_checked']);
            })
            ->paginate(config('common.list_count'));
        return $purchaseOrders;
    }
    public function createOrUpdate($request)
    {
        $data = $request->all();
        $staff = UserData();
        $items = json_decode($request->items);
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $latest = PurchaseOrder::orderBy('created_at', 'desc')->first();
            $count = 4;
            $no = (new CommonPurchaseOrder())->getUniqueId($latest, $count);
            $po_id = "PO" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['po_id'] = $po_id;
            $data['created_by'] = $staff->id;
            $po = PurchaseOrder::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($items as $item) {
                // if (!isset($item->id) || $item->id==null) {
                if (isset($item->id) && $item->id !== null) {
                    $item_data['id'] = $item->id;
                } else {
                    $item_data['id'] = null;
                }
                $item_data['quantity'] = $item->quantity;
                $item_data['purchase_order_id'] = $po->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['amount'] = $item->amount;
                $po->items()->updateOrCreate(['id' => $item_data['id']], $item_data);
            }
            DB::commit();
            return $po;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

    }

    public function updateData(array $data, int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            $purchaseOrder->update($data);
        }
        return $purchaseOrder;
    }

    public function deleteData(int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            $purchaseOrder->delete();
            return true;
        } else {
            return false;
        }
    }

    public function detail($purchaseOrder)
    {
        $purchaseOrder->items = $purchaseOrder->items;
        return $purchaseOrder;
    }

    // po mean purchase order
    public function updateKitchenAndFinancePO(string $condition, array $data, int $id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        if ($purchaseOrder) {
            if ($condition == 'kitchen') {
                $purchaseOrder->kitchen_check_id = $data['kitchen_id'];
                $purchaseOrder->kitchen_check_time = CurrentTime();
                $purchaseOrder->save();
            } elseif ($condition == 'financial') {
                $purchaseOrder->financial_check_id = $data['financial_id'];
                $purchaseOrder->financial_check_time = CurrentTime();
                $purchaseOrder->save();
            }
        }
        return $purchaseOrder;
    }

    public function deletePurchaseOrderItem($id)
    {
        $po_item = PurchaseOrderItem::find($id);
        if ($po_item) {
            $po_item->delete();
            ResponseMessage("Delete successfully", 200);
        } else {
            ResponseMessage("Data isn't found ", 404);
        }
    }

    public function updateIsCheck($request)
    {
        $staff = UserData();
        DB::beginTransaction();
        try {
            $model = Model($request->type)::find($request->id);
            $this->validateModel($model, $staff, $request->type);
            if ($model) {
                if ($staff->hasRoles('Manager')) {
                    $column = 'manager_check';
                    $is_column = 'is_manager_checked';
                    $status = 'manager_checked';
                } else if ($staff->hasRoles('Financial')) {
                    $column = 'financial_check';
                    $is_column = 'is_financial_checked';
                    $status = 'financial_checked';
                } else if ($staff->hasRoles('MD')) {
                    $column = 'md_check';
                    $is_column = 'is_md_checked';
                    $status = 'md_checked';
                }
                if ($request->type == 'purchase_order_item') {
                    // $is_column = 'is_manager_checked';
                    $model->$is_column = $request->value;
                    $model->save();
                }
                if ($request->type == 'purchase_order') {
                    $column_id = $column . '_' . 'id';
                    $column_time = $column . '_' . 'time';
                    // $column='is_financial_check';
                    $items = $this->existIsCheck($model, $is_column, 0);
                    if ($items->isNotEmpty()) {
                        ResponseMessage('Some items are left to check', 422);
                    }
                    $staff->hasRoles('MD') ?
                    $model->$is_column = 1 : $model->$column_id = $staff->id;
                    $model->$column_time = now();
                    $model->status = $status;
                    $model->save();

                    #store after md confimed
                    if ($staff->hasRoles('MD')) {
                        (new StoreInventory())->inventoryAction($model, 'in', 'purchase_order');
                    }
                }
                DB::commit();
                ResponseMessage('Update successfully', 200);
            }
            ResponseMessage("Data isn't found", 404);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
        
    }

    public function existIsCheck($model, $is_column, $value)
    {
        return $model->items->whereIn($is_column, $value)->values();
    }

    public function validateModel($model, $staff, $type)
    {
        if ($model) {
            if ($staff->hasRoles('Staff')) ResponseMessage("Permission isn't allowed", 422);
            if($type=='purchase_order'){
                if ($staff->hasRoles('Manager')) {
                    if ($model->manager_check_id!=null) ResponseMessage('This Purchase Order is already checked By Manager', 419);
                } else if ($staff->hasRoles('Financial')) {
                    if ($model->financial_check_id!=null) ResponseMessage('This Purchase Order is already checked By Financial', 422);
                } else if ($staff->hasRoles('MD')) {
                    if ($model->is_md_checked) ResponseMessage('This Purchase Order is already checked By MD', 422);
                    if(!$model->createdBy->department->inventory){  
                        ResponseMessage('Inventory is required',422);
                    }
                }
            }
        }
    }
}
