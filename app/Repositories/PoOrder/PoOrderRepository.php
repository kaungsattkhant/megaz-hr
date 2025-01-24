<?php

namespace App\Repositories\PoOrder;

use App\Models\PoOrder;
use App\Models\ItemLeft;
use App\Models\PoInvoice;
use App\Models\ArrivalItem;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PoOrderItemResource;

class PoOrderRepository implements PoOrderRepositoryInterface
{

  public function getPoOrderItems(Request $request)
  {
    // return $this->test($request);
    $poOrderItems = DB::table('purchase_order_items as poi')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->join('uoms as u', 'poi.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
      ->join('purchase_orders as po', 'poi.purchase_order_id', '=', 'po.id')
      ->where('poi.is_md_checked', true)
      ->where('po.status', 'md_checked')
      ->select(
        'poi.item_id',
        'i.name as item_name',
        'u.name as uom_name',
        'bu.name as base_uom_name',
        'uc.conversion as uom_conversion',
        'poi.base_uom_id',
        'poi.base_uom_quantity',
        'poi.uom_id',
        'poi.uom_quantity',
        'poi.uom_conversion_id',
        DB::raw('SUM(poi.quantity) as total_quantity'),
        DB::raw('SUM(poi.amount) as total_amount'),
        DB::raw('GROUP_CONCAT(po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('(
            SELECT GROUP_CONCAT(
                CONCAT(
                    "{",
                    "\"id\":", po_item.id,
                    ",\"item_name\":\"", i.name, "\"",
                    ",\"quantity\":", po_item.quantity,
                    ",\"amount\":", po_item.amount,
                    ",\"base_uom_id\":", po_item.base_uom_id,
                    ",\"base_uom_name\":\"", bu_sub.name, "\"",
                    ",\"base_uom_quantity\":", po_item.base_uom_quantity,
                    ",\"uom_id\":", po_item.uom_id,
                    ",\"uom_name\":\"", u_sub.name, "\"",
                    ",\"uom_quantity\":", po_item.uom_quantity,
                    ",\"uom_conversion_id\":", po_item.uom_conversion_id,
                    ",\"uom_conversion\":", uc.conversion,
                    ",\"uom_conversion\":", uc_sub.conversion,
                    ",\"purchase_order\":{",
                        "\"id\":", po.id,
                        ",\"po_id\":\"", po.po_id, "\"",
                        ",\"total_price\":\"", po.total_price, "\"",
                        ",\"date\":\"", po.date, "\"",
                        ",\"status\":\"", po.status, "\"",
                    "}}"
                ) SEPARATOR ","
            )
            FROM purchase_order_items po_item
                INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
                INNER JOIN items i ON po_item.item_id = i.id
                INNER JOIN uoms u_sub ON po_item.uom_id = u_sub.id
                INNER JOIN uoms bu_sub ON po_item.base_uom_id = bu_sub.id
                INNER JOIN uom_conversions uc_sub ON po_item.uom_conversion_id = uc_sub.id
            WHERE po_item.item_id = poi.item_id
                AND po_item.is_md_checked = true
                AND po.status = "md_checked"
        ) as purchase_order_details')
      )
      ->groupBy(
        'poi.item_id',
        'i.name',
        'poi.uom_conversion_id',
        'uc.conversion',
        'poi.base_uom_id',
        'bu.name',
        'poi.base_uom_quantity',
        'poi.uom_id',
        'u.name',
        'poi.uom_quantity'
      )
      ->paginate(config('common.list_count'));

    foreach ($poOrderItems->items() as $item) {
      if ($item->purchase_order_details) {
        $item->purchase_order_details = json_decode('[' . $item->purchase_order_details . ']');
      }
    }

    return $poOrderItems;
  }
  public function test($request)
  {


    $poOrderItems = DB::table('purchase_order_items as poi')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->join('uoms as u', 'poi.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
      ->join('purchase_orders as po', 'poi.purchase_order_id', '=', 'po.id')
      ->where('poi.is_md_checked', true)
      ->where('po.status', 'md_checked')
      ->select(
        'poi.item_id',
        'i.name as item_name',
        'u.name as uom_name',
        'bu.name as base_uom_name',
        'uc.conversion as uom_conversion',
        'poi.base_uom_id',
        'poi.base_uom_quantity',
        'poi.uom_id',
        'poi.uom_quantity',
        'poi.uom_conversion_id',
        DB::raw('SUM(CASE 
        WHEN (
            SELECT COALESCE(SUM(item_lefts.quantity), 0)
            FROM item_lefts
            WHERE item_lefts.purchase_order_id = poi.purchase_order_id
        ) = 0 THEN poi.quantity
        ELSE (
            SELECT COALESCE(SUM(item_lefts.quantity), 0)
            FROM item_lefts
            WHERE item_lefts.purchase_order_id = poi.purchase_order_id
        )
    END) as total_quantity'),
        DB::raw('SUM(poi.amount) as total_amount'),
        DB::raw('GROUP_CONCAT(po.id SEPARATOR ", ") as po_ids'),
        DB::raw('GROUP_CONCAT(po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('(
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    "id", po_item.id,
                    "item_name", i.name,
                    "amount", po_item.amount,
                    "quantity", po_item.quantity,
                    "base_uom_id", po_item.base_uom_id,
                    "base_uom_quantity", po_item.base_uom_quantity,
                    "uom_id", po_item.uom_id,
                    "uom_quantity", po_item.uom_quantity,
                    "uom_conversion_id", po_item.uom_conversion_id,
                    "uom_conversion", uc.conversion,
                   "quantity", CASE 
    WHEN (
        SELECT COALESCE(SUM(item_lefts.quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    ) = 0 THEN po_item.quantity
    ELSE (
        SELECT COALESCE(SUM(item_lefts.quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    )
END,
"quantity", CASE 
    WHEN (
        SELECT COALESCE(SUM(item_lefts.uom_quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    ) = 0 THEN po_item.uom_quantity
    ELSE (
        SELECT COALESCE(SUM(item_lefts.uom_quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    )
END,
"quantity", CASE 
    WHEN (
        SELECT COALESCE(SUM(item_lefts.base_uom_quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    ) = 0 THEN po_item.base_uom_quantity
    ELSE (
        SELECT COALESCE(SUM(item_lefts.base_uom_quantity), 0)
        FROM item_lefts
        WHERE item_lefts.purchase_order_id = po_item.purchase_order_id
    )
END,
                    "purchase_order", JSON_OBJECT(
                        "id", po.id,
                        "po_id", po.po_id,
                        "total_price", po.total_price,
                        "date", po.date,
                        "status", po.status
                    )
                )
            )
            FROM purchase_order_items po_item
            INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
            INNER JOIN uom_conversions uc ON po_item.uom_conversion_id = uc.id
            INNER JOIN items i ON po_item.item_id = i.id
            WHERE po_item.item_id = poi.item_id
            AND po_item.is_md_checked = true
            AND po.status = "md_checked"
        ) as purchase_order_details')

      )
      ->groupBy(
        'poi.item_id',
        'i.name',
        'poi.uom_conversion_id',
        'uc.conversion',
        'poi.base_uom_id',
        'bu.name',
        'poi.base_uom_quantity',
        'poi.uom_id',
        'u.name',
        'poi.uom_quantity',
      )
      ->paginate(config('common.list_count'));

    foreach ($poOrderItems as $item) {
      $item->purchase_order_details = json_decode($item->purchase_order_details, true);
    }
    return $poOrderItems;
  }

  public function storePoOrderItems($validatedData)
  {
    DB::beginTransaction();
    try {

      $validatedData['created_by'] = UserData()->id;
      $poOrder = PoOrder::create($validatedData);

      if (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) {

        $purchaseOrderItem = PurchaseOrderItem::where('purchase_order_id', $validatedData['purchase_order_id'])
          ->where('item_id', $validatedData['item_id'])
          ->first();
        if (!$purchaseOrderItem) {
          ResponseMessage('Purchase Order item not found', 404);
        }
        if ($purchaseOrderItem->quantity < $poOrder->quantity) {
          ResponseMessage('The order quantity exceeds the available quantity.', 419);
        }

        $remainingQuantity = null;
        if ($purchaseOrderItem->quantity > $poOrder->quantity) {
          $remainingQuantity = $purchaseOrderItem->quantity - $poOrder->quantity;

          if ($remainingQuantity < 0) {
            ResponseMessage('Later Buy Quantity must be less than original quantity', 419);
          }
          $itemLeftData = [
            'base_uom_id' => $validatedData['base_uom_id'],
            'base_uom_quantity' => $purchaseOrderItem->base_uom_quantity - $validatedData['base_uom_quantity'],
            'uom_id' => $validatedData['uom_id'],
            'uom_quantity' => $purchaseOrderItem->uom_quantity - $validatedData['uom_quantity'],
            'uom_conversion_unit_id' => $validatedData['uom_conversion_unit_id'],
            'quantity' => $remainingQuantity,
            'amount' => $validatedData['amount'],
            'created_by' => UserData()->id,
            'item_leftable_id' => $poOrder->id,
            'item_leftable_type' => $validatedData['item_leftable_type'],
            'purchase_order_id' => $validatedData['purchase_order_id'],
          ];
          ItemLeft::create($itemLeftData);
        }
      }
      DB::commit();
      return $poOrder;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  // public function getPoOrderArrivalList(Request $request)
  // {
  //   $poOrders = DB::table('po_orders')

  //     ->join('purchase_orders as po', 'po_orders.purchase_order_id', '=', 'po.id')

  //     ->join('purchase_order_items as poi', 'po.id', '=', 'poi.purchase_order_id')

  //     ->join('items as i', 'poi.item_id', '=', 'i.id')

  //     ->join('suppliers as s', 'po_orders.supplier_id', '=', 's.id')

  //     ->join('item_prices as ip', 'po_orders.item_price_id', '=', 'ip.id')

  //     ->join('uoms as u', 'poi.uom_id', '=', 'u.id')

  //     ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')

  //     ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
  //     ->select(
  //       'i.id as item_id',
  //       'i.name as item_name',
  //       DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
  //       DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
  //       DB::raw('SUM(poi.quantity) as total_quantity'),
  //       DB::raw('SUM(poi.amount) as total_amount'),
  //       'u.name as uom_name',
  //       'bu.name as base_uom_name',
  //       'uc.conversion as uom_conversion',
  //       'ip.price as item_price',
  //       DB::raw('(
  //               SELECT GROUP_CONCAT(
  //                   CONCAT(
  //                       "{",
  //                       "\"id\":", po_item.id,
  //                       ",\"item_name\":\"", i_sub.name, "\"",
  //                       ",\"quantity\":", po_item.quantity,
  //                       ",\"amount\":", po_item.amount,
  //                       ",\"base_uom_id\":", po_item.base_uom_id,
  //                       ",\"base_uom_name\":\"", bu_sub.name, "\"",
  //                       ",\"base_uom_quantity\":", po_item.base_uom_quantity,
  //                       ",\"uom_id\":", po_item.uom_id,
  //                       ",\"uom_name\":\"", u_sub.name, "\"",
  //                       ",\"uom_quantity\":", po_item.uom_quantity,
  //                       ",\"uom_conversion_id\":", po_item.uom_conversion_id,
  //                       ",\"uom_conversion\":", uc_sub.conversion,
  //                       ",\"purchase_order\":{",
  //                           "\"id\":", po.id,
  //                           ",\"po_id\":\"", po.po_id, "\"",
  //                           ",\"total_price\":\"", po.total_price, "\"",
  //                           ",\"date\":\"", po.date, "\"",
  //                           ",\"status\":\"", po.status, "\"",
  //                       "},",
  //                       "\"supplier\":{",
  //                           "\"id\":", s.id,
  //                           ",\"name\":\"", s.name, "\"",
  //                       "}}"
  //                   ) SEPARATOR ","
  //               )
  //               FROM purchase_order_items po_item
  //                   INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
  //                   INNER JOIN po_orders po_order ON po_order.purchase_order_id = po.id
  //                   INNER JOIN items i_sub ON po_item.item_id = i_sub.id
  //                   INNER JOIN uoms u_sub ON po_item.uom_id = u_sub.id
  //                   INNER JOIN uoms bu_sub ON po_item.base_uom_id = bu_sub.id
  //                   INNER JOIN uom_conversions uc_sub ON po_item.uom_conversion_id = uc_sub.id
  //                   INNER JOIN suppliers s ON po_order.supplier_id = s.id
  //               WHERE po_item.item_id = po_order.item_id
  //                   AND po_item.is_md_checked = true
  //                   AND po.status = "md_checked"
  //           ) as purchase_order_details')
  //     )
  //     ->groupBy(
  //       'i.id',
  //       'i.name',
  //       'u.name',
  //       'bu.name',
  //       'ip.price',
  //       'uc.conversion'
  //     )
  //     ->paginate(config('common.list_count'));  // Pagination

  //   // Decode the purchase_order_details column for each item
  //   foreach ($poOrders->items() as $item) {
  //     if ($item->purchase_order_details) {
  //       $item->purchase_order_details = json_decode('[' . $item->purchase_order_details . ']', true);
  //     }
  //   }

  //   return $poOrders;
  // }

  // correct groupBy
  public function getPoOrderArrivalList(Request $request)
  {
    $poOrders = DB::table('po_orders as poOrder')
      ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
      ->join('purchase_order_items as poi', 'po.id', '=', 'poi.purchase_order_id')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
      ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
      ->select(
        'i.id as item_id',
        'i.name as item_name',
        DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('SUM(poOrder.quantity) as total_quantity'),
        DB::raw('SUM(poOrder.amount) as total_amount'),
        'u.id as uom_id',
        'u.name as uom_name',
        'bu.id as base_uom_id',
        'bu.name as base_uom_name',
        'uc.id as uom_conversion_id',
        'uc.conversion as uom_conversion',
        'ip.id as item_price_id',
        'ip.price as item_price',
      )
      ->join('uoms as u', 'poOrder.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poOrder.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poOrder.uom_conversion_unit_id', '=', 'uc.id')
      ->groupBy(
        'i.id',
        'i.name',
        'u.id',
        'u.name',
        'bu.id',
        'bu.name',
        'ip.id',
        'ip.price',
        'uc.id',
        'uc.conversion'
      )
      ->paginate(config('common.list_count'));
    return $poOrders;
  }

  // public function getPoOrderArrivalListByItemId($itemId)
  // {
  //   $poOrders = PoOrder::with(['purchaseOrder', 'uomConversion', 'uom', 'baseUom', 'item', 'brand', 'supplier', 'itemPrice'])
  //     ->where('item_id', $itemId)
  //     ->get();

  //   return PoOrderItemResource::collection($poOrders);
  // }



  public function getPoOrderArrivalListByItemId($itemId)
  {
    $poOrders = PoOrder::with(['purchaseOrder', 'uomConversion', 'uom', 'baseUom', 'item', 'brand', 'supplier', 'itemPrice'])
      ->where('item_id', $itemId)
      ->get();

    foreach ($poOrders as $poOrder) {

      $arrivalItems = ArrivalItem::where('po_order_id', $poOrder->id)->get();

      if ($arrivalItems->isNotEmpty()) {
        foreach ($arrivalItems as $arrivalItem) {

          $itemLeft = ItemLeft::where('item_leftable_id', $arrivalItem->id)
            ->where('item_leftable_type', 'arrival_item')
            ->get();

          if ($itemLeft->isNotEmpty()) {

            $totalItemLeftQuantity = $itemLeft->sum('quantity');
            $totalItemLeftAmount = $itemLeft->sum('amount');
            $totalItemLeftBaseUomQuantity = $itemLeft->sum('base_uom_quantity');
            $totalItemLeftUomQuantity = $itemLeft->sum('uom_quantity');

            $poOrder->quantity -= $totalItemLeftQuantity;
            $poOrder->amount -= $totalItemLeftAmount;
            $poOrder->base_uom_quantity -= $totalItemLeftBaseUomQuantity;
            $poOrder->uom_quantity -= $totalItemLeftUomQuantity;
          } else {
            $poOrder->quantity = 0;
            $poOrder->amount = 0;
            $poOrder->base_uom_quantity = 0;
            $poOrder->uom_quantity = 0;
          }
        }
      }
    }
    return PoOrderItemResource::collection($poOrders);
  }


  public function getInvoiceBySupplier($supplierId)
  {
    $invoices = PoInvoice::with(['arrivalItems.poOrder'])
      ->whereHas('arrivalItems.poOrder', function ($query) use ($supplierId) {
        $query->where('supplier_id', $supplierId);
      })
      ->get();

    return $invoices;
  }

  public function storePoArrivalItems($validatedData)
  {
    DB::beginTransaction();
    try {

      if (isset($validatedData['is_new_invoice']) && $validatedData['is_new_invoice'] == 1) {
        $invoiceData = [
          'invoice_no' => $validatedData['invoice_no'],
          'date_time' => now(),
          'total_invoice_amount' =>  $validatedData['amount'],
          'created_by' => UserData()->id,
        ];
        $newPoInvoice = PoInvoice::create($invoiceData);

        $arrivalItemData = [
          'base_uom_id'  => $validatedData['base_uom_id'],
          'base_uom_quantity'  => $validatedData['base_uom_quantity'],
          'uom_id' => $validatedData['uom_id'],
          'uom_quantity' => $validatedData['uom_quantity'],
          'quantity' => $validatedData['quantity'],
          'amount' => $validatedData['amount'],
          'po_invoice_id' => $newPoInvoice->id,
          'po_order_id' =>  $validatedData['po_order_id'],
          'created_by' => UserData()->id,
        ];
        $arrivalItem = ArrivalItem::create($arrivalItemData);
      }

      if (isset($validatedData['po_invoice_id'])) {

        $poInvoice = PoInvoice::find($validatedData['po_invoice_id']);

        if ($poInvoice) {

          $poInvoice->total_invoice_amount += $validatedData['amount'];
          $poInvoice->save();
          $arrivalItemData = [
            'base_uom_id'  => $validatedData['base_uom_id'],
            'base_uom_quantity'  => $validatedData['base_uom_quantity'],
            'uom_id' => $validatedData['uom_id'],
            'uom_quantity' => $validatedData['uom_quantity'],
            'quantity' => $validatedData['quantity'],
            'amount' => $validatedData['amount'],
            'po_invoice_id' => $poInvoice->id,
            'po_order_id' =>  $validatedData['po_order_id'],
            'created_by' => UserData()->id,
          ];
          $arrivalItem = ArrivalItem::create($arrivalItemData);
        } else {

          throw new \Exception('PoInvoice not found for the given ID.');
        }
      }

      if (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) {
        $poOrder = PoOrder::where('id', $validatedData['po_order_id'])->first();
        if ($poOrder->quantity <  $arrivalItem->quantity) {
          ResponseMessage('The arrival  quantity exceeds the available quantity.', 419);
        }

        $remainingQuantity = null;
        if ($poOrder->quantity >  $arrivalItem->quantity) {

          $remainingQuantity = $poOrder->quantity -  $arrivalItem->quantity;

          if ($remainingQuantity < 0) {
            ResponseMessage('Later Buy Quantity must be less than original quantity', 419);
          }
          $itemLeftData = [
            'base_uom_id' => $validatedData['base_uom_id'],
            'base_uom_quantity' => $poOrder->base_uom_quantity - $validatedData['base_uom_quantity'],
            'uom_id' => $validatedData['uom_id'],
            'uom_quantity' => $poOrder->uom_quantity - $validatedData['uom_quantity'],
            'uom_conversion_unit_id' => $validatedData['uom_conversion_unit_id'],
            'quantity' => $remainingQuantity,
            'amount' => $validatedData['amount'],
            'created_by' => UserData()->id,
            'item_leftable_id' => $arrivalItem->id,
            'item_leftable_type' => $validatedData['item_leftable_type'],
            'purchase_order_id' => $poOrder->purchase_order_id,
          ];

          ItemLeft::create($itemLeftData);
        }
      }

      DB::commit();
      return $arrivalItem;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getSupplierLeadTime($supplierId)
  {
    $result = null;
    $totalAvgLeadTime = 0;
    $itemsData = [];
    $itemLeadTimes = [];

    $poOrderlist = PoOrder::with(['arrivalItem', 'item'])
      ->where('supplier_id', $supplierId)
      ->get();

    $poOrderIds = $poOrderlist->pluck('id');
    $totalArrivalItemCount = ArrivalItem::whereIn('po_order_id', $poOrderIds)
      ->count();

    foreach ($poOrderlist as $poOrder) {
      $orderTime = new \Carbon\Carbon($poOrder->created_at);

      foreach ($poOrder->arrivalItem as $arrival) {
        $arrivalTime = new \Carbon\Carbon($arrival->created_at);

        $leadTime = abs($arrivalTime->diffInSeconds($orderTime));
        $avgLeadtime = $leadTime / $totalArrivalItemCount;
        $totalAvgLeadTime +=  $avgLeadtime;
        // Group lead times by item_id (handle duplicates)
        if (!isset($itemLeadTimes[$poOrder->item->id])) {
          $itemLeadTimes[$poOrder->item->id] = [
            'total_lead_time' => 0,
            'count' => 0
          ];
        }

        // Add lead time and increase count for unique items
        $itemLeadTimes[$poOrder->item->id]['total_lead_time'] += $leadTime;
        $itemLeadTimes[$poOrder->item->id]['count']++;
      }
    }

    // Calculate average lead time per unique item and format
    foreach ($itemLeadTimes as $itemId => $data) {

      $avgOrderTime = $data['total_lead_time'] / $data['count'];
      $formattedAvgOrderTime = $this->formatTime($avgOrderTime);

      $item = $poOrderlist->firstWhere('item.id', $itemId)->item;
      $itemName = $item ? $item->name : 'null';

      $itemsData[] = [
        'item_id' => $itemId,
        'item_name' => $itemName,
        'average_order_time' => $formattedAvgOrderTime,
      ];
    }
    // Calculate total average lead time
    $totalAverageLeadTimeFormatted = $this->formatTime($totalAvgLeadTime);

    $result = [
      'supplier_id' => $supplierId,
      'supplier_name' => $poOrder->supplier->name,
      'total_average_lead_time' => isset($totalAverageLeadTimeFormatted) ? $totalAverageLeadTimeFormatted : 'null',
      'details' => $itemsData,
    ];

    return $result;
  }

  private function formatTime($totalTimeInSeconds)
  {
    $days = floor($totalTimeInSeconds / (60 * 60 * 24));
    $hours = floor(($totalTimeInSeconds % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor(($totalTimeInSeconds % (60 * 60)) / 60);

    if ($days > 0) {
      return "{$days} days, {$hours} hours, {$minutes} minutes";
    } else {
      return "{$hours} hours, {$minutes} minutes";
    }
  }

  public function getInvoices(Request $request)
  {
    $invoices = PoInvoice::with(['arrivalItems.poOrder.PurchaseOrder', 'arrivalItems.poOrder.Item', 'arrivalItems.poOrder.Supplier'])
      ->paginate(config('common.list_count'));

    return $invoices;
  }
}
