<?php

namespace App\Repositories\PurchaseOrder;

use App\Events\SendNotification as EventsSendNotification;
use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\SendNotification\SendNotification;
use App\Http\Action\Transaction\PurchaseOrderTransaction;
use App\Models\PoGrn;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderItemLeft;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    private $select = ['po_id', 'total_price', 'created_by', 'manager_check_id', 'manager_check_time', 'financial_check_id', 'financial_check_time', 'is_md_check', 'status', 'created_at', 'updated_at'];
    use SendNotification;
    private $morphMapName;
    public function listAllData(Request $request)
    {
        $staff = UserData();
        $purchaseOrders = PurchaseOrder::with(['items.item', 'createdBy', 'managerCheckedBy', 'financialCheckedBy'])
            ->orderBy('id', 'desc')
            ->when(checkDepartmentAndRoles('HR', ['Staff']), function ($q) use ($staff) {
                $q->where('created_by', $staff->id);
            })
            ->when(checkDepartmentAndRoles('HR', ['Manager']), function ($q) {
                $q->whereIn('status', ['manager_checked', 'created'])
                    ->orWhere('manager_check_id', UserData()->id);
            })
            ->when(checkDepartmentAndRoles('Finance', ['Manager']), function ($q) {
                $q->whereIn('status', ['manager_checked', 'financial_checked'])
                    ->orWhere('financial_check_id', UserData()->id);
            })
            ->when(checkDepartmentAndRoles('Management', ['MD']), function ($q) {
                $q->whereIn('status', ['md_checked', 'financial_checked']);
            })
            ->paginate(config('common.list_count'));
        return $purchaseOrders;
    }
    public function createOrUpdate($request)
    {
        // dd($request->all());
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
            $no = (new CommonPurchaseOrder())->getUniqueId($latest,'po_id',$count);
            $po_id = "PO" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['po_id'] = $po_id;

            if (isset($request->is_grn) && ($request->is_grn || $request->is_grn == "1")) {
                $data['is_bought'] = 1;
            }

            if (!$request->id) {
                $data['created_by'] = $staff->id;
            }
            $po = PurchaseOrder::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($items as $item) {
                if (isset($item->id) && $item->id !== null) {
                    $item_data['id'] = $item->id;
                } else {
                    $item_data['id'] = null;
                }
                $item_data['quantity'] = $item->quantity;
                if (checkDepartmentAndRoles('HR', ['Staff'])) {
                    $item_data['original_quantity'] = $item->quantity;
                } else {
                    $item_data['original_quantity'] = (isset($item->later_buy) && $item->later_buy)?$item->original_quantity :$item->quantity;
                }
                $item_data['purchase_order_id'] = $po->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['amount'] = $item->amount;
                $item_data['uom_id'] = $item->uom_id;
                $item_data['uom_conversion_id'] = $item->uom_conversion_id;
                // $purchaseOrderItem=PurchaseOrderItem::find($item_data['id']);
                if (isset($item->later_buy) && $item->later_buy) {

                    $purchaseOrderItem = $po->items()->where('id', $item_data['id'])->first();
                    // dd($purchaseOrderItem);
                    // dd($item);
                        if ($purchaseOrderItem) {
                            if ($item->quantity > $purchaseOrderItem->quantity) {
                                ResponseMessage('Later Buy Quantity must be less than original quantity', 419);
                            }
                            if ($item->quantity < $purchaseOrderItem->quantity) {
                                $quantity = $purchaseOrderItem->original_quantity - $item->quantity;

                                if ($quantity < 0) {
                                    ResponseMessage('Later Buy Quantity must be less than original quantity', 419);
                                }
                                $column = null;
                                if (checkDepartmentAndRoles('HR', ['Manager'])) {
                                    $column = 'quantity_by_manager';
                                } elseif (!$po->is_md_checked && checkDepartmentAndRoles('Finance', ['Manager'])) {
                                    $column = 'quantity_by_financial';
                                } elseif (checkDepartmentAndRoles('Management', ['MD'])) {
                                    $column = 'quantity_by_md';
                                } elseif ($po->is_md_checked && checkDepartmentAndRoles('Finance', ['Manager'])) {
                                    $column = 'quantity_after_md';
                                }
                                if ($column != null) {
                                    $po_left = PurchaseOrderItemLeft::updateOrCreate(
                                        [
                                            'purchase_order_item_id' => $item->id,
                                        ],
                                        [
                                            'purchase_order_item_id' => $item->id,
                                            'quantity' => $quantity,
                                            $column => $item->quantity,
                                        ]);
                                }
                            }
                        }
                }
                if ($request->is_grn) {
                    if ($item->supplier_id != null && $item->invoice_amount != null && $item->invoice_no != null) {
                        $item_data['is_grn'] = 1;
                        if($item->invoice_amount<$item->quantity*$item->amount){
                            // (new PurchaseOrderTransaction())->createTransaction($po, $morphMapName, $request->cash_account_id); #create transaction
                        }
                    }
                    #grn store
                    $po_grn=$this->storeGRN($item);
                    #grn
                }
                $po_item = $po->items()->updateOrCreate(['id' => $item_data['id']], $item_data);
            }
            if ($request->is_grn && $po) {
                $morphMapName = RelationMorphName($po);
                // $po->po_grn_id=$po_grn->ids
                (new PurchaseOrderTransaction())->createTransaction($po, $morphMapName, $request->cash_account_id); #create transaction
            }
            if (!isset($request->id)) {
                $users = $this->getUserByRole('HR', ['Manager']);
                $data = [
                    'date' => $po->created_at,
                    'title' => 'You have received a new PO to confirm',
                    'body' => 'New Purchase Order',
                ];
                #send old notificaiton
                #end
                if($users->isNotEmpty()){
                    $this->send($po, $users, $data);
                }
            }
            DB::commit();
            return $po;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function storeGRN($item)
    {
        if ($item->supplier_id != null && $item->invoice_amount != null && $item->invoice_no != null) {
            return PoGrn::create([
                'quantity' => $item->quantity,
                'supplier_id' => $item->supplier_id,
                'invoice_amount' => $item->invoice_amount,
                'invoice_no' => $item->invoice_no,
                'purchase_order_item_id' => $item->id,
            ]);
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
        $purchaseOrder->uom=$purchaseOrder->uom;
        $purchaseOrder->items = $purchaseOrder->items;
        $purchaseOrder->items->load('purchaseOrderItemLeft');
        $purchaseOrder->items->load('item.suppliers');
        foreach ($purchaseOrder->items as $item) {
            $item->later_buy = $item->purchaseOrderItemLeft ? 1 : 0;
        }
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
                if (checkDepartmentAndRoles('HR', ['Manager'])) {
                    $column = 'manager_check';
                    $is_column = 'is_manager_checked';
                    $status = 'manager_checked';
                } else if (checkDepartmentAndRoles('Finance', ['Manager'])) {
                    $column = 'financial_check';
                    $is_column = 'is_financial_checked';
                    $status = 'financial_checked';
                } else if (checkDepartmentAndRoles('Management', ['MD'])) {
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
                    // $items = $this->existIsCheck($model, $is_column, 0);
                    // if ($items->isNotEmpty()) {
                    //     ResponseMessage('Some items are left to check', 422);
                    // }
                    checkDepartmentAndRoles('Management', ['MD']) ?
                    $model->$is_column = 1 : $model->$column_id = $staff->id;
                    $model->$column_time = now();
                    $model->status = $status;
                    $model->save();
                    $this->existIsCheckAndUpdate($model, $is_column, $request->value);
                    #send notification by specific role
                    #notification
                    $users = collect([]);
                    if (checkDepartmentAndRoles('HR', ['Manager'])) {
                        $users = $this->getUserByRole('Finance', ['Manager']);
                        $title = 'You have received a new PO to confirm';
                    } else if (checkDepartmentAndRoles('Finance', ['Manager'])) {
                        $users = $this->getUserByRole('Management', ['MD']);
                        $title = 'You have received a new PO to confirm';
                    } else if (checkDepartmentAndRoles('Management', ['MD'])) {
                        $users = $this->getUserByRole('Finance', ['Manager']);
                        $title = 'You have received a new PO to confirm From MD';
                    }
                    $data = [
                        'date' => $model->created_at,
                        'title' => $title,
                        'body' => 'New Purchase Order',
                    ];
                    $this->send($model, $users, $data);
                    #end notification
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

    public function existIsCheckAndUpdate($model, $is_column, $value)
    {
        return $model->items()->update([
            $is_column => $value,
        ]);
    }

    public function validateModel($model, $staff, $type)
    {
        if ($model) {
            if (checkDepartmentAndRoles('HR', ['Staff'])) {
                ResponseMessage("Permission isn't allowed", 422);
            }
            $departmentName = UserData()->department->name;
            $roles = UserData()->roles;
            if ($type == 'purchase_order') {
                if (checkDepartmentAndRoles('HR', ['Manager'])) {
                    if ($model->manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Manager', 419);
                    }

                } else if (checkDepartmentAndRoles('Finance', ['Manager'])) {
                    if ($model->financial_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Financial', 422);
                    }

                } else if (checkDepartmentAndRoles('Management', ['MD'])) {
                    if ($model->is_md_checked) {
                        ResponseMessage('This Purchase Order is already checked By MD', 422);
                    }

                    // if (!$model->createdBy->department->inventory) {
                    //     ResponseMessage('Inventory is required', 422);
                    // }
                }
            }
        }
    }

    public function boughtPurchaseOrder($request)
    {
        #no need
        // $ids = $request->ids;
        // $po = PurchaseOrder::whereIn('id', $ids)
        //     ->update([
        //         'is_bought' => $request->value,
        //     ]);
        // foreach ($ids as $id) {
        //     $model = PurchaseOrder::find($id);
        //     if ($model) {
        //         (new StoreInventory())->inventoryAction($model, 'in', 'purchase_order');
        //     }
        // }
        // $po ? ResponseMessage('PO bought successfully', 200) : ResponseMessage('PO bought Fail', 422);
        #end
    }

    public function getPurchaseOrderItemConfirmationList($request)
    {
        return PurchaseOrderItem::with(['purchase_order'])
            ->orderBy('id', 'desc')
            ->where('is_grn', 1)
            ->paginate(config('common.list_count'));
    }

    public function confirmPurchaseOrderItem($request)
    {
        DB::beginTransaction();
        try {
            if (checkDepartmentAndRoles('Inventory', ['Staff'])) {
                $po_item = PurchaseOrderItem::find($request->id);
                if ($po_item) {
                    if ($po_item->is_confirmed == 1) {
                        ResponseMessage('Already checked', 200);
                    }
                    $po_item->is_confirmed = 1;
                    $po_item->save();
                    #store inventory
                    if(!UserData()->department->inventory){
                        ResponseMessage("Inventory is required",419);
                    }
                    $inventoryId=UserData()->department->inventory->inventory_id;
                    $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($po_item->purchase_order, 'purchase_order', 'in');
                    (new StoreInventory($inventoryId))->storeItemToInventory($inventoryLedger, $po_item);
                    #store inventory
                    DB::commit();
                    ResponseMessage('Update Successfully', 200);
                }
                ResponseMessage('Not Found', 404);
            }
            ResponseMessage("Permission isn't access", 403);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

    }

}
