<?php

namespace App\Repositories\PurchaseOrder;

use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\SendNotification\SendNotification;
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
    public function listAllData(Request $request)
    {
        $staff = UserData();
        $purchaseOrders = PurchaseOrder::with(['items.item', 'createdBy', 'managerCheckedBy', 'financialCheckedBy'])
            ->orderBy('id', 'desc')
            ->when($staff->hasRoles('Staff'), function ($q) use ($staff) {
                $q->where('created_by', $staff->id);
            })
            ->when($staff->hasRoles('Manager'), function ($q) {
                $q->whereIn('status', ['manager_checked', 'created'])
                    ->orWhere('manager_check_id', UserData()->id);
            })
            ->when($staff->hasRoles('Financial'), function ($q) {
                $q->whereIn('status', ['manager_checked', 'financial_checked'])
                    ->orWhere('financial_check_id', UserData()->id);
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
                if ($staff->hasRoles('Staff')) {
                    $item_data['original_quantity'] = $item->quantity;
                } else {
                    $item_data['original_quantity'] = $item->original_quantity;
                }
                $item_data['purchase_order_id'] = $po->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['amount'] = $item->amount;

                // $purchaseOrderItem=PurchaseOrderItem::find($item_data['id']);
                if (isset($item->later_buy) && $item->later_buy) {
                    $purchaseOrderItem = $po->items()->where('id', $item_data['id'])->first();
                    if ($purchaseOrderItem) {
                        if ($item->quantity < $purchaseOrderItem->quantity) {
                            $quantity = $purchaseOrderItem->original_quantity - $item->quantity;
                            $column = null;
                            if ($staff->hasRoles('Manager')) {
                                $column = 'quantity_by_manager';
                            } elseif (!$po->is_md_checked && $staff->hasRoles('Financial')) {
                                $column = 'quantity_by_financial';
                            } elseif ($staff->hasRoles('MD')) {
                                $column = 'quantity_by_md';
                            } elseif ($po->is_md_checked && $staff->hasRoles('Financial')) {
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

                $po_item = $po->items()->updateOrCreate(['id' => $item_data['id']], $item_data);
                if ($request->is_grn) {
                    $this->storeGRN($po, $item);

                }
            }
            if ($request->is_grn) {
                if ($po) {
                    (new StoreInventory())->inventoryAction($po, 'in', 'purchase_order');
                }
            }
            if (!isset($request->id)) {
                $users = $this->getUserByRole(['Manager']);
                $data = [
                    'date' => $po->created_at,
                    'title' => 'You have received a new PO to confirm',
                    'body' => 'New Purchase Order',
                ];
                $this->send($po, $users, $data);
            }
            DB::commit();
            return $po;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }

    }

    public function storeGRN($po, $item)
    {
        return PoGrn::create([
            'quantity' => $item->quantity,
            'supplier_id' => $item->supplier_id,
            'invoice_amount' => $item->invoice_amount,
            'invoice_no' => $item->invoice_no,
            'item_id' => $item->item_id,
            'purchase_order_id' => $po->id,
        ]);

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
        $purchaseOrder->items->load('purchaseOrderItemLeft');
        foreach ($purchaseOrder->items as $item) {
            $item->later_buy = $item->purchaseOrderItemLeft ? 1 : 0;
        }
        $purchaseOrder->items->load('item.suppliers');
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
                    // $items = $this->existIsCheck($model, $is_column, 0);
                    // if ($items->isNotEmpty()) {
                    //     ResponseMessage('Some items are left to check', 422);
                    // }
                    $staff->hasRoles('MD') ?
                    $model->$is_column = 1 : $model->$column_id = $staff->id;
                    $model->$column_time = now();
                    $model->status = $status;
                    $model->save();
                    $this->existIsCheckAndUpdate($model, $is_column, $request->value);
                    #send notification by specific role
                    #notification
                    $users = collect([]);
                    if ($staff->hasRoles('Manager')) {
                        $users = $this->getUserByRole(['Financial']);
                        $title = 'You have received a new PO to confirm';
                    } else if ($staff->hasRoles('Financial')) {
                        $users = $this->getUserByRole(['MD']);
                        $title = 'You have received a new PO to confirm';
                    } else if ($staff->hasRoles('MD')) {
                        $users = $this->getUserByRole(['Financial']);
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
            if ($staff->hasRoles('Staff')) {
                ResponseMessage("Permission isn't allowed", 422);
            }

            if ($type == 'purchase_order') {
                if ($staff->hasRoles('Manager')) {
                    if ($model->manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Manager', 419);
                    }

                } else if ($staff->hasRoles('Financial')) {
                    if ($model->financial_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Financial', 422);
                    }

                } else if ($staff->hasRoles('MD')) {
                    if ($model->is_md_checked) {
                        ResponseMessage('This Purchase Order is already checked By MD', 422);
                    }

                    if (!$model->createdBy->department->inventory) {
                        ResponseMessage('Inventory is required', 422);
                    }
                }
            }
        }
    }

    public function boughtPurchaseOrder($request)
    {
        $ids = $request->ids;
        $po = PurchaseOrder::whereIn('id', $ids)
            ->update([
                'is_bought' => $request->value,
            ]);
        foreach ($ids as $id) {
            $model = PurchaseOrder::find($id);
            if ($model) {
                (new StoreInventory())->inventoryAction($model, 'in', 'purchase_order');
            }
        }
        $po ? ResponseMessage('PO bought successfully', 200) : ResponseMessage('PO bought Fail', 422);
    }
}
