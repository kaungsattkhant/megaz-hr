<?php

namespace App\Repositories\PoOrder;

use App\Models\PoOrder;
use App\Models\ItemLeft;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class PoOrderRepository implements PoOrderRepositoryInterface
{

  public function getPoOrderItems(Request $request)
  {
    return $this->test($request);
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

    $leftsSubquery = DB::table('item_lefts')
      ->select('item_id', DB::raw('SUM(quantity) as total_quantity'))
      ->groupBy('item_id');

    $poOrderSubquery = DB::table('po_orders')
      ->select('item_id', DB::raw('SUM(quantity) as total_po_order_quantity'))
      ->groupBy('item_id');

    $poOrderItems = DB::table('purchase_order_items as poi')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->joinSub($leftsSubquery, 'i_lefts', function ($join) {
        $join->on('i_lefts.item_id', '=', 'i.id');
      })
      ->joinSub($poOrderSubquery, 'po_orders', function ($join) {
        $join->on('po_orders.item_id', '=', 'i.id');
      })
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
        'poi.uom_id',
        'poi.uom_conversion_id',
        DB::raw('COALESCE(i_lefts.total_quantity, 0) as left_quantity'),// Default to 0 if no data
        DB::raw('COALESCE(po_orders.total_po_order_quantity, 0) as po_quantity'),// Default to 0 if no data
        DB::raw('(
          SELECT JSON_ARRAYAGG(
              JSON_OBJECT(
                  "id", po_item.id,
                  "item_name", i.name,
                  "item_id", i.id,
                  "amount", po_item.amount,
                  "quantity", po_item.quantity,
                  "base_uom_id", po_item.base_uom_id,
                  "base_uom_quantity", po_item.base_uom_quantity,
                  "uom_id", po_item.uom_id,
                  "uom_quantity", po_item.uom_quantity,
                  "uom_conversion_id", po_item.uom_conversion_id,
                  "uom_conversion", uc.conversion,
                   "quantity", 
CASE 
    WHEN (
        SELECT COALESCE(SUM(il.quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
    ) = po_quantity 
    THEN po_item.quantity
    ELSE (
        SELECT COALESCE(SUM(il.quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
    )
END,
"uom_quantity", CASE 
    WHEN (
        SELECT COALESCE(SUM(il.uom_quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
    ) = po_quantity 
    THEN po_item.quantity
    ELSE (
        SELECT COALESCE(SUM(il.uom_quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
    )
END,
"base_uom_quantity", CASE 
    WHEN (
        SELECT COALESCE(SUM(il.base_uom_quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
    ) = po_quantity 
    THEN po_item.quantity
    ELSE (
        SELECT COALESCE(SUM(il.base_uom_quantity), 0)
        FROM item_lefts il
        WHERE il.purchase_order_id = po_item.purchase_order_id
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
        'poi.uom_id',
        'u.name',
        'i_lefts.total_quantity',
        'po_orders.total_po_order_quantity'
      )
      ->paginate(config('common.list_count'));

    //     $poOrderItems = DB::table('purchase_order_items as poi')
//       ->join('items as i', 'poi.item_id', '=', 'i.id')
//       ->joinSub($leftsSubquery, 'i_lefts', function ($join) {
//         $join->on('i_lefts.item_id', '=', 'i.id');
//       })
//       ->join('uoms as u', 'poi.uom_id', '=', 'u.id')
//       ->join('uoms as bu', 'poi.base_uom_id', '=', 'bu.id')
//       ->join('uom_conversions as uc', 'poi.uom_conversion_id', '=', 'uc.id')
//       ->join('purchase_orders as po', 'poi.purchase_order_id', '=', 'po.id')
//       ->where('poi.is_md_checked', true)
//       ->where('po.status', 'md_checked')
//       ->select(
//         'poi.item_id',
//         'i.name as item_name',
//         'u.name as uom_name',
//         'bu.name as base_uom_name',
//         'uc.conversion as uom_conversion',
//         'poi.base_uom_id',
//         'poi.uom_id',
//         // 'poi.uom_quantity',
//         'poi.uom_conversion_id',
//         'i_lefts.total_quantity as left_quantity',
// //         DB::raw('SUM(CASE 
// //     WHEN (
// //         SELECT COALESCE(SUM(il.quantity), 0)
// //         FROM item_lefts il
// //         WHERE il.purchase_order_id = poi.purchase_order_id
// //         AND il.id = (
// //             SELECT MAX(inner_il.id)
// //             FROM item_lefts inner_il
// //             WHERE inner_il.purchase_order_id = il.purchase_order_id
// //         )
// //     ) = poi.po_quantity THEN poi.quantity
// //     ELSE (
// //         SELECT COALESCE(SUM(il.quantity), 0)
// //         FROM item_lefts il
// //         WHERE il.purchase_order_id = poi.purchase_order_id
// //         AND il.id = (
// //             SELECT MAX(inner_il.id)
// //             FROM item_lefts inner_il
// //             WHERE inner_il.purchase_order_id = il.purchase_order_id
// //         )
// //     )
// // END) as total_quantity'),
//         DB::raw('(
//           SELECT JSON_ARRAYAGG(
//               JSON_OBJECT(
//                   "id", po_item.id,
//                   "item_name", i.name,
//                   "item_id", i.id,
//                   "amount", po_item.amount,
//                   "quantity", po_item.quantity,
//                   "base_uom_id", po_item.base_uom_id,
//                   "base_uom_quantity", po_item.base_uom_quantity,
//                   "uom_id", po_item.uom_id,
//                   "uom_quantity", po_item.uom_quantity,
//                   "uom_conversion_id", po_item.uom_conversion_id,
//                   "uom_conversion", uc.conversion,
//                    "quantity", 
// CASE 
//     WHEN (
//         SELECT COALESCE(SUM(il.quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     ) = po_quantity 
//     THEN po_item.quantity
//     ELSE (
//         SELECT COALESCE(SUM(il.quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     )
// END,
// "uom_quantity", CASE 
//     WHEN (
//         SELECT COALESCE(SUM(il.uom_quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     ) = po_quantity 
//     THEN po_item.quantity
//     ELSE (
//         SELECT COALESCE(SUM(il.uom_quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     )
// END,
// "base_uom_quantity", CASE 
//     WHEN (
//         SELECT COALESCE(SUM(il.base_uom_quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     ) = po_quantity 
//     THEN po_item.quantity
//     ELSE (
//         SELECT COALESCE(SUM(il.base_uom_quantity), 0)
//         FROM item_lefts il
//         WHERE il.purchase_order_id = po_item.purchase_order_id
//     )
// END,
//                   "purchase_order", JSON_OBJECT(
//                       "id", po.id,
//                       "po_id", po.po_id,
//                       "total_price", po.total_price,
//                       "date", po.date,
//                       "status", po.status
//                   )
//               )
//           )
//           FROM purchase_order_items po_item
//           INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
//           INNER JOIN uom_conversions uc ON po_item.uom_conversion_id = uc.id
//           INNER JOIN items i ON po_item.item_id = i.id
//           WHERE po_item.item_id = poi.item_id
//           AND po_item.is_md_checked = true
//           AND po.status = "md_checked"
//       ) as purchase_order_details')
//       )
//       ->groupBy(
//         'poi.item_id',
//         'i.name',
//         'poi.uom_conversion_id',
//         'uc.conversion',
//         'poi.base_uom_id',
//         'bu.name',
//         'poi.uom_id',
//         'u.name',
//         'i_lefts.total_quantity as left_quantity'
//       )
//       ->paginate(config('common.list_count'));

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
}
