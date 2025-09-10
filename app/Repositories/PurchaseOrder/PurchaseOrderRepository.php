<?php

namespace App\Repositories\PurchaseOrder;

use App\Models\Item;
use App\Models\Brand;
use App\Models\PoGrn;
use App\Models\ItemPrice;
use App\Models\SupplierItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Laravel\Reverb\Loggers\Log;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItemLeft;
use Illuminate\Database\Eloquent\Model;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\SendNotification\SendNotification;
use App\Http\Action\Transaction\PurchaseOrderTransaction;
use App\Events\SendNotification as EventsSendNotification;
use App\Http\Action\Common\PurchaseOrder as CommonPurchaseOrder;

class PurchaseOrderRepository implements PurchaseOrderRepositoryInterface
{
    private $select = [
        'po_id',
        'total_price',
        'created_by',
        'manager_check_id',
        'manager_check_time',
        'financial_check_id',
        'financial_check_time',
        'is_md_checked',
        'status',
        'created_at',
        'updated_at',
        'purchased_date_time',
        'procurement_manager_check_id',
        'procurement_manager_check_time',
        'type',
        'event_id',
    ];
    use SendNotification;
    private $morphMapName;
    public function listAllData(Request $request)
    {
        
        $staff = UserData();
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $po_id = $request->po_id;
        if ($po_id) {
            $po_id = str_replace(' ', '', $po_id);
        }
        $purchaseOrders = PurchaseOrder::with([
            'items.item',
            'items.baseUom',
            'items.uomConversion',
            'createdBy',
            'managerCheckedBy',
            'financialCheckedBy',
            'procurementCheckedBy'
        ])->when((isset($from_date) && !empty($from_date)), function ($q) use ($from_date) {
            $q->where('date', '>=', $from_date);
        })->when((isset($to_date) && !empty($to_date)), function ($q) use ($to_date) {
            $q->where('date', '<=', $to_date);
        })
            ->when((isset($from_date) && isset($to_date) && !empty($from_date) && !empty($to_date)), function ($q) use ($from_date, $to_date) {

                $q->whereBetween('date', [$from_date, $to_date]);
            })->when((isset($po_id) && !empty($po_id)), function ($q) use ($po_id) {
                $q->where('po_id', 'like', '%' . $po_id . '%');
            })
            ->orderBy('id', 'desc');

        if (checkRoles(['Staff'])) {
            $purchaseOrders->where('created_by', $staff->id);
        }

        if (checkDepartmentAndRoles('HR', ['Manager'])) {
            $purchaseOrders->where(function ($query) {
                $query->whereIn('status', ['manager_checked', 'created'])
                    ->orWhere('manager_check_id', UserData()->id);
            });
        }
        if (checkDepartmentAndRoles('Procurement', ['Manager'])) {
            $purchaseOrders->where(function ($query) {
                $query->whereIn('status', ['manager_checked'])
                    ->orWhere('procurement_manager_check_id', UserData()->id)
                    ->orWhere('created_by', UserData()->id);
            });
        }

        if (checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
            $purchaseOrders->where(function ($query) {
                $query->whereIn('status', ['procurement_manager_checked', 'manager_checked', 'financial_checked'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('financial_check_id', UserData()->id)
                            ->orWhere('created_by', UserData()->id);
                    });
            });
        }

        if (checkDepartmentAndRoles('Management', ['MD'])) {
            $purchaseOrders->whereIn('status', ['md_checked', 'financial_checked']);
        }


        $paginatedOrders = $purchaseOrders->paginate(config('common.list_count'));

        return $paginatedOrders;
    }
    public function createOrUpdate($request)
    {
        $data = $request->all();
        $staff = UserData();
        $items = json_decode($request->items);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ResponseMessage('Invalid JSON data provided for purchase order items.', 400);
        }
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $data['total_price'] = (int) $data['total_price']; //wrong data from frontend
            $latest = PurchaseOrder::orderBy('created_at', 'desc')->first();
            $count = 4;
            $no = (new CommonPurchaseOrder())->getUniqueId($latest, 'po_id', $count);
            $po_id = "PO" . '-' . str_pad($no, $count, "0", STR_PAD_LEFT) . '-' . now()->timestamp;
            $data['po_id'] = $po_id;

            if (!$request->id) {
                $data['created_by'] = $staff->id;
                if (checkRoles(['Manager'])) {
                    $data['manager_check_id'] = $staff->id;
                    $data['manager_check_time'] = now();
                    $data['status'] = 'manager_checked';
                }
            }
            if (isset($data['type']) && $data['type'] === "event") {
                if (empty($data['event_id'])) {
                    return ResponseMessage('event_id is required when type is event', 422);
                }
            }
            $po = PurchaseOrder::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($items as $item) {
                $supplierExist = SupplierItem::where('item_id', $item->item_id)
                    ->where('is_active', 1)
                    ->exists();
                if (!$supplierExist) {
                    return ResponseMessage('Supplier not found for this item', 422);
                }
                if (isset($item->id) && $item->id !== null) {
                    $item_data['id'] = $item->id;
                } else {
                    $item_data['id'] = null;
                }
                $item_data['quantity'] = $item->quantity;
                if (checkRoles(['Staff'])) {

                    $item_data['original_quantity'] = $item->quantity;
                } else {
                    $item_data['original_quantity'] = (isset($item->later_buy) && $item->later_buy) ? $item->original_quantity : $item->quantity;
                }
                $item_data['purchase_order_id'] = $po->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['brand_id'] = $item->brand_id;
                $item_data['amount'] = $item->amount;
                $item_data['unit_price'] = $item->unit_price ?? null;
                $item_data['uom_id'] = $item->uom_id;
                $item_data['uom_conversion_id'] = $item->uom_conversion_id;
                $item_data['base_uom_id'] = $item->base_uom_id;
                $item_data['base_uom_quantity'] = $item->base_uom_quantity;
                $item_data['uom_quantity'] = $item->uom_quantity;
                $item_data['remark'] = $item->remark ?? null;
                $item_data['is_exceed_max_limitation'] = $item->is_exceed_max_limitation ?? 0;

                if (isset($item->later_buy) && $item->later_buy) {

                    $purchaseOrderItem = $po->items()->where('id', $item_data['id'])->first();

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
                            // if (checkRoles(['Manager'])) {
                            if (checkDepartmentAndRoles('HR', ['Manager'])) {
                                $column = 'quantity_by_manager';
                            } elseif (!$po->is_md_checked && checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
                                $column = 'quantity_by_financial';
                            } elseif (checkDepartmentAndRoles('Management', ['MD'])) {
                                $column = 'quantity_by_md';
                            } elseif ($po->is_md_checked && checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
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
                                    ]
                                );
                            }
                        }
                    }
                }

                $po_item = $po->items()->updateOrCreate(['id' => $item_data['id']], $item_data);
            }

            if (!isset($request->id)) {
                // $users = $this->getUserByRole('HR', ['Manager']);
                $departmentId = $staff->department_id;
                $users = $this->getUserByDepartment($departmentId, ['Manager']);
                $data = [
                    'date' => $po->created_at,
                    'title' => 'You have received a new PO to confirm',
                    'body' => 'New Purchase Order',
                ];
                #send old notificaiton
                #end
                if ($users->isNotEmpty()) {
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
    public function checkLimitation($request)
    {
        $item = Item::find($request['item_id']);
        if (!$item) {
            ResponseMessage("Item not found", 404);
        }
        if ($item->limitation_type === "uom") {
            if (
                (isset($item->max_limit_uom_quantity) && $request['uom_quantity'] > $item->max_limit_uom_quantity) ||
                (isset($item->max_limit_base_uom_quantity) && $request['base_uom_quantity'] > $item->max_limit_base_uom_quantity)
            ) {
                ResponseMessage("Quantity exceeds the allowed limit.", 422);
            }
        } elseif ($item->limitation_type === "amount") {
            if (isset($item->amount) && $request['amount'] > $item->amount) {
                ResponseMessage("Amount exceeds the allowed limit.", 422);
            }
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
        if (!$purchaseOrder) {
            ResponseMessage('Purchase order not found', 404);
        }
        $purchaseOrder->load([
            'items.brand',
            'items.uom',
            'items.purchaseOrderItemLeft',
            'items.item.suppliers',
            'items.baseUom',
            'items.uomConversion'
        ]);
        // $purchaseOrder->brand = $purchaseOrder->brand;
        // $purchaseOrder->uom = $purchaseOrder->uom;
        // $purchaseOrder->items = $purchaseOrder->items;
        // $purchaseOrder->items->load('purchaseOrderItemLeft');
        // $purchaseOrder->items->load('item.suppliers');
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
        if (checkMultipleFeaturePermission('purchase-order.confirm')) {
            $staff = UserData();
            DB::beginTransaction();
            try {
                $model = Model($request->type)::find($request->id);
                $this->validateModel($model, $staff, $request->type);
                if ($model) {
                    if (checkDepartmentAndRoles('HR', ['Manager'])) {
                        // if (!checkDepartmentAndRoles('Finance', ['Manager']) && checkRoles(['Manager']) && !checkDepartmentAndRoles('Procurement', ['Manager'])) {
                        $column = 'manager_check';
                        $is_column = 'is_manager_checked';
                        $status = 'manager_checked';
                    } else if (checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
                        $column = 'financial_check';
                        $is_column = 'is_financial_checked';
                        $status = 'financial_checked';
                    } else if (checkDepartmentAndRoles('Management', ['MD'])) {
                        $column = 'md_check';
                        $is_column = 'is_md_checked';
                        $status = 'md_checked';
                    } else if (checkDepartmentAndRoles('Procurement', ['Manager'])) {
                        $column = 'procurement_manager_check';
                        $is_column = 'is_procurement_manager_checked';
                        $status = 'procurement_manager_checked';
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
                        // if (checkDepartmentAndRoles('HR', ['Manager'])) {
                        if (checkDepartmentAndRoles('HR', ['Manager'])) {
                            $users = $this->getUserByRole('Procurement', ['Manager']);
                            $title = 'You have received a new PO to confirm';
                        } elseif (checkDepartmentAndRoles('Procurement', ['Manager'])) {
                            $users = $this->getUserByRole('Finance', ['Chief Accountant']);
                            $title = 'You have received a new PO to confirm From Procurement';
                        } else if (checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
                            $users = $this->getUserByRole('Management', ['MD']);
                            $title = 'You have received a new PO to confirm';
                        } else if (checkDepartmentAndRoles('Management', ['MD'])) {
                            $users = $this->getUserByRole('Finance', ['Chief Accountant']);
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
        ResponseMessage('Permission denied ', 410);

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
            if (checkDepartmentAndRoles('HR', ['Staff']) || checkDepartmentAndRoles('HR', ['Supervisor'])) {
                ResponseMessage("Permission isn't allowed", 422);
            }
            $departmentName = UserData()->department->name;
            $roles = UserData()->roles;
            if ($type == 'purchase_order') {
                if (checkDepartmentAndRoles('HR', ['Manager'])) {
                    if ($model->manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Manager', 419);
                    }
                } else if (checkDepartmentAndRoles('Procurement', ['Manager'])) {
                    if ($model->procurement_manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Procurement Manager', 422);
                    }
                    if ($model->manager_check_id == null || $model->manager_check_time == null) {
                        ResponseMessage('Required confirmation from HR(Manager) ! ', 422);
                    }
                } else if (checkDepartmentAndRoles('Finance', ['Chief Accountant'])) {
                    if ($model->financial_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Financial', 422);
                    }
                    if ($model->procurement_manager_check_id == null || $model->procurement_manager_check_time == null) {
                        ResponseMessage('Required confirmation from Procurement', 422);
                    }
                } else if (checkDepartmentAndRoles('Management', ['MD'])) {
                    if ($model->is_md_checked) {
                        ResponseMessage('This Purchase Order is already checked By MD', 422);
                    }
                    if ($model->financial_check_id == null || $model->financial_check_time == null) {
                        ResponseMessage('Required confirmation from Finance', 422);
                    }
                    // if (!$model->createdBy->department->inventory) {
                    //     ResponseMessage('Inventory is required', 422);
                    // }
                } else {
                    ResponseMessage("Permission isn't allowed", 422);
                }
            }
        }
    }



    public function getPurchaseOrderItemConfirmationList($request)
    {
        return PurchaseOrderItem::with(['purchase_order', 'item', 'baseUom', 'uomConversion'])
            ->orderBy('id', 'desc')
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
                    if (!UserData()->department->inventory) {
                        ResponseMessage("Inventory is required", 419);
                    }
                    $inventoryId = UserData()->department->inventory->inventory_id;
                    $inventoryLedger = (new StoreInventory(inventoryId: $inventoryId))->storeToInventoryLedger($po_item->purchase_order, 'purchase_order', 'in');
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

    public function getAvgPriceByBrand($itemId, $brandId)
    {
        $totalPrice = 0;
        $count = 0;

        $supplierItems = SupplierItem::with('item_price')
            ->where('item_id', $itemId)
            ->where('brand_id', $brandId)
            ->get();
        foreach ($supplierItems as $supplierItem) {
            $itemPrice = $supplierItem->item_price;
            if ($itemPrice) {
                $totalPrice += $itemPrice->price;
                $count++;
            }
        }
        $averagePrice = $count > 0 ? $totalPrice / $count : 0;
        return $averagePrice;
    }
}
