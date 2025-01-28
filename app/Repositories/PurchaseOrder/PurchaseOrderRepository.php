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
        'procurement_manager_check_time'
    ];
    use SendNotification;
    private $morphMapName;
    public function listAllData(Request $request)
    {
        $staff = UserData();
        $purchaseOrders = PurchaseOrder::with([
            'items.item',
            'items.baseUom',
            'items.uomConversion',
            'createdBy',
            'managerCheckedBy',
            'financialCheckedBy',
            'procurementCheckedBy'
        ])
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

        if (checkDepartmentAndRoles('Finance', ['Manager'])) {
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
                if (checkRoles(['Staff'])) {

                    $item_data['original_quantity'] = $item->quantity;
                } else {
                    $item_data['original_quantity'] = (isset($item->later_buy) && $item->later_buy) ? $item->original_quantity : $item->quantity;
                }
                $item_data['purchase_order_id'] = $po->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['brand_id'] = $item->brand_id;
                $item_data['amount'] = $item->amount;
                $item_data['unit_price'] = $item->unit_price;
                $item_data['uom_id'] = $item->uom_id;
                $item_data['uom_conversion_id'] = $item->uom_conversion_id;
                $item_data['base_uom_id'] = $item->base_uom_id;
                $item_data['base_uom_quantity'] = $item->base_uom_quantity;
                $item_data['uom_quantity'] = $item->uom_quantity;
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
            'items.uom',
            'items.purchaseOrderItemLeft',
            'items.item.suppliers',
            'items.baseUom',
            'items.uomConversion'
        ]);
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
                } else if (checkDepartmentAndRoles('Finance', ['Manager'])) {
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
                        $users = $this->getUserByRole('Finance', ['Manager']);
                        $title = 'You have received a new PO to confirm From Procurement';
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
                } else if (checkDepartmentAndRoles('Procurement', ['Manager'])) {
                    if ($model->procurement_manager_check_id != null) {
                        ResponseMessage('This Purchase Order is already checked By Procurement Manager', 422);
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
        $avgItemPrice = SupplierItem::where('item_id', $itemId)
            ->where('brand_id', $brandId)
            ->whereHas('item_price') // Ensure related item_price exists
            ->with('item_price') // Load the related item_price
            ->join('item_prices', 'supplier_items.id', '=', 'item_prices.supplier_item_id') // Join with item_prices
            ->avg('item_prices.price'); // Calculate average price
        return $avgItemPrice ? (float) $avgItemPrice : 0; 

        // $supplierItems = SupplierItem::where('item_id', $itemId)
        //     ->where('brand_id', $brandId)
        //     ->get();

        // $totalPrice = 0;
        // $totalCount = 0;
        // foreach ($supplierItems as $supplierItem) {
        //     $itemPrice = $supplierItem->item_price;

        //     if ($itemPrice) {
        //         $totalPrice += $itemPrice->price;
        //         $totalCount++;
        //     }
        // }

        // $avgItemPrice = $totalCount > 0 ? $totalPrice / $totalCount : 0;

        // return $avgItemPrice;
    }
}
