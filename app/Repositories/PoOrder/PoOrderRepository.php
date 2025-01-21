<?php

namespace App\Repositories\PoOrder;

use App\Models\PoOrder;
use App\Models\ItemLeft;
use App\Models\PoInvoice;
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

      if (isset($validatedData['later_by'])) {
        $purchaseOrderItem = PurchaseOrderItem::where('purchase_order_id', $validatedData['purchase_order_id'])
          ->where('item_id', $validatedData['item_id'])
          ->first();

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

  public function getPoOrderArrivalList(Request $request)
  {
    $poOrders = DB::table('po_orders')
      ->join('purchase_order_items as poi', 'po_orders.id', '=', 'poi.purchase_order_id')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->join('suppliers as s', 'po_orders.supplier_id', '=', 's.id')
      ->join('purchase_orders as po', 'po_orders.purchase_order_id', '=', 'po.id')
      ->join('item_prices as ip', 'po_orders.item_price_id', '=', 'ip.id')
      ->select(
        'i.id as item_id',
        'i.name as item_name',
        DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('SUM(poi.quantity) as total_quantity'),
        DB::raw('SUM(poi.amount) as total_amount'),
        'u.id as uom_id',
        'u.name as uom_name',
        'bu.id as base_uom_id',
        'bu.name as base_uom_name',
        'uc.id as uom_conversion_id',
        'uc.conversion as uom_conversion',
        'ip.id as item_price_id',
        'ip.price as item_price',
      )
      ->join('uoms as u', 'poi.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
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

  public function getPoOrderArrivalListByItemId($itemId)
  {
    $poOrders = PoOrder::with(['purchaseOrder', 'uomConversion', 'uom', 'baseUom', 'item', 'brand', 'supplier', 'itemPrice'])
      ->where('item_id', $itemId)
      ->get();

    return PoOrderItemResource::collection($poOrders);
  }


  //   public function getPoOrderArrivalList(Request $request)
  // {

  //     $poOrders = DB::table('po_orders')
  //         ->join('purchase_order_items as poi', 'po_orders.id', '=', 'poi.purchase_order_id')
  //         ->join('items as i', 'poi.item_id', '=', 'i.id')
  //         ->join('suppliers as s', 'po_orders.supplier_id', '=', 's.id')
  //         ->join('purchase_orders as po', 'po_orders.purchase_order_id', '=', 'po.id')
  //         ->join('item_prices as ip', 'po_orders.item_price_id', '=', 'ip.id')
  //         ->select(
  //             'i.id as item_id',
  //             'i.name as item_name',
  //             DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
  //             DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
  //             DB::raw('SUM(poi.quantity) as total_quantity'),
  //             DB::raw('SUM(poi.amount) as total_amount'),
  //             'u.name as uom_name',
  //             'bu.name as base_uom_name',
  //             'uc.conversion as uom_conversion',
  //             'ip.price as item_price',
  //             DB::raw('(
  //                 SELECT GROUP_CONCAT(
  //                     CONCAT(
  //                         "{",
  //                         "\"id\":", po_item.id,
  //                         ",\"item_name\":\"", i_sub.name, "\"",
  //                         ",\"quantity\":", po_item.quantity,
  //                         ",\"amount\":", po_item.amount,
  //                         ",\"base_uom_id\":", po_item.base_uom_id,
  //                         ",\"base_uom_name\":\"", bu_sub.name, "\"",
  //                         ",\"base_uom_quantity\":", po_item.base_uom_quantity,
  //                         ",\"uom_id\":", po_item.uom_id,
  //                         ",\"uom_name\":\"", u_sub.name, "\"",
  //                         ",\"uom_quantity\":", po_item.uom_quantity,
  //                         ",\"uom_conversion_id\":", po_item.uom_conversion_id,
  //                         ",\"uom_conversion\":", uc_sub.conversion,
  //                         ",\"purchase_order\":{",
  //                             "\"id\":", po.id,
  //                             ",\"po_id\":\"", po.po_id, "\"",
  //                             ",\"total_price\":\"", po.total_price, "\"",
  //                             ",\"date\":\"", po.date, "\"",
  //                             ",\"status\":\"", po.status, "\"",
  //                         "},",
  //                         "\"supplier\":{",
  //                             "\"id\":", s.id,
  //                             ",\"name\":\"", s.name, "\"",
  //                         "}}"
  //                     ) SEPARATOR ","
  //                 )
  //                 FROM purchase_order_items po_item
  //                     INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
  //                     INNER JOIN items i_sub ON po_item.item_id = i_sub.id
  //                     INNER JOIN uoms u_sub ON po_item.uom_id = u_sub.id
  //                     INNER JOIN uoms bu_sub ON po_item.base_uom_id = bu_sub.id
  //                     INNER JOIN uom_conversions uc_sub ON po_item.uom_conversion_id = uc_sub.id
  //                     INNER JOIN suppliers s ON po.supplier_id = s.id
  //                 WHERE po_item.item_id = poi.item_id
  //                     AND po_item.is_md_checked = true
  //                     AND po.status = "md_checked"
  //             ) as purchase_order_details')
  //         )
  //         ->join('uoms as u', 'poi.uom_id', '=', 'u.id')
  //         ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')
  //         ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
  //         ->groupBy(
  //             'i.id',
  //             'i.name',
  //             'u.name',
  //             'bu.name',
  //             'ip.price',
  //             'uc.conversion'
  //         )
  //         ->paginate(config('common.list_count')); 

  //     foreach ($poOrders->items() as $item) {
  //         if ($item->purchase_order_details) {

  //             $item->purchase_order_details = json_decode('[' . $item->purchase_order_details . ']', true);
  //         }
  //     }

  //     return $poOrders;
  // }

  public function getInvoiceBySupplier($supplierId)
  {
    $invoices = PoInvoice::with(['arrivalItems.poOrder'])
      ->whereHas('arrivalItems.poOrder', function ($query) use ($supplierId) {
        $query->where('supplier_id', $supplierId);
      })
      ->get();

    return $invoices;
  }
}
