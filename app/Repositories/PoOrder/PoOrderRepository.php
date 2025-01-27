<?php

namespace App\Repositories\PoOrder;

use App\Models\PoOrder;
use App\Models\ItemLeft;
use App\Models\PoInvoice;
use App\Models\ArrivalItem;
use Illuminate\Http\Request;
use App\Models\InventoryLedger;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryLedgerItem;
use App\Traits\PoInvoiceTransaction;
use App\Http\Resources\PoInvoiceResource;
use App\Http\Resources\PoOrderItemResource;
use App\Models\Inventory;

class PoOrderRepository implements PoOrderRepositoryInterface
{

  use PoInvoiceTransaction;
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
    $poClass = 'po_order';
    // $leftsSubquery = DB::table('item_lefts')
    //   ->select('item_id', DB::raw('SUM(quantity) as total_left_quantity'))
    //   ->groupBy('item_id');
    $leftsSubquery = DB::table('item_lefts')
      ->join('po_orders', function ($join) use ($poClass) {
        $join->on('item_lefts.item_leftable_id', '=', 'po_orders.id')
          ->where('item_lefts.item_leftable_type', '=', $poClass);
      })
      ->select('po_orders.item_id', 'po_orders.purchase_order_id', DB::raw('SUM(item_lefts.quantity) as total_left_quantity'))
      ->groupBy('po_orders.item_id', 'po_orders.purchase_order_id');


    $poOrderSubquery = DB::table('po_orders')
      ->select(
        'item_id',
        DB::raw('SUM(quantity) as total_po_order_quantity'),
        DB::raw('GROUP_CONCAT(po_orders.purchase_order_id SEPARATOR ",") as po_order_ids'),
      )
      ->groupBy('item_id',);


    $poOrderItems = DB::table('purchase_order_items as poi')
      ->join('items as i', 'poi.item_id', '=', 'i.id')
      ->leftJoinSub($leftsSubquery, 'i_lefts', function ($join) {
        $join->on('i_lefts.item_id', '=', 'i.id');
      })
      ->leftJoinSub($poOrderSubquery, 'po_orders', function ($join) {
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
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('
        COALESCE(
    SUM(
        CASE 
            WHEN po_orders.po_order_ids IS NULL THEN poi.quantity
            WHEN FIND_IN_SET(poi.purchase_order_id, po_orders.po_order_ids) = 0 THEN poi.quantity
            ELSE 0 
        END
    ), 0
) AS total_purchase_order_quantity
    '),
        DB::raw('COALESCE(i_lefts.total_left_quantity, 0) as left_quantity'), // Default to 0 if no data
        DB::raw('COALESCE(po_orders.total_po_order_quantity, 0) as po_order_quantity'), // Default to 0 if no data
        DB::raw('COALESCE(po_orders.po_order_ids, "null") as po_order_ids'),
        DB::raw('GROUP_CONCAT(DISTINCT poi.purchase_order_id SEPARATOR ", ") as po_ids'),
        // DB::raw('
        //     GROUP_CONCAT(
        //         DISTINCT 
        //         CASE 
        //             WHEN poi.purchase_order_id NOT IN (po_order_ids) =0 THEN poi.purchase_order_id
        //         END 
        //         SEPARATOR ", "
        //     ) as unmatched_po_ids
        // '),
        DB::raw('
        GROUP_CONCAT(
            DISTINCT 
            CASE 
                WHEN FIND_IN_SET(poi.purchase_order_id, po_order_ids) = 0 THEN poi.purchase_order_id
            END 
            SEPARATOR ", "
        ) as unmatched_po_ids
    '),
        DB::raw('(
      COALESCE(i_lefts.total_left_quantity, 0) + 
      COALESCE(
          CASE 
              WHEN po_orders.po_order_ids IS NULL THEN 
                  SUM(poi.quantity)
              ELSE 
                  SUM(CASE 
                      WHEN FIND_IN_SET(poi.purchase_order_id, po_order_ids)  = 0 THEN poi.quantity
                      ELSE 0 
                  END)
          END, 0)
  ) as total_quantity'),
        DB::raw('(
          SELECT JSON_ARRAYAGG(
              JSON_OBJECT(
                  "id", po_item.purchase_order_id,
                  "item_name", i.name,
                  "item_name", po_orders.po_order_ids,
                  "item_id", i.id,
                  "amount", po_item.amount,
                  "base_uom_id", po_item.base_uom_id,
                  "base_uom_quantity", po_item.base_uom_quantity,
                  "uom_id", po_item.uom_id,
                  "uom_quantity", po_item.uom_quantity,
                  "uom_conversion_id", po_item.uom_conversion_id,
                  "uom_conversion", uc.conversion,
                  "total_purchase_order_quantity", (
                SELECT COALESCE(
                    SUM(
                        CASE
                            WHEN po_orders.po_order_ids IS NULL THEN po_item_sub.quantity
                            ELSE 
                                CASE 
                                    WHEN FIND_IN_SET(po_item.purchase_order_id,  po_orders.po_order_ids) =0
                                    THEN po_item_sub.quantity 
                                    ELSE 0 
                                END
                        END
                    ), 0
                )
                FROM purchase_order_items po_item_sub
                WHERE po_item_sub.item_id = po_item.item_id
                  AND po_item_sub.purchase_order_id = po_item.purchase_order_id
            ),
                 "total_po_order_quantity", (
    SELECT COALESCE(SUM(po_order_sub.quantity), 0)
    FROM po_orders po_order_sub
    WHERE po_order_sub.item_id = po_item.item_id
      AND po_order_sub.purchase_order_id = po_item.purchase_order_id
),
                   "total_left_quantity", (
    SELECT COALESCE(SUM(lefts.total_left_quantity), 0)
    FROM (
        SELECT 
            po_orders.item_id,
            po_orders.purchase_order_id,
            SUM(il.quantity) as total_left_quantity
        FROM item_lefts il
        INNER JOIN po_orders ON il.item_leftable_id = po_orders.id
        WHERE il.item_leftable_type = "' . $poClass . '"
        GROUP BY po_orders.item_id, po_orders.purchase_order_id
    ) as lefts
    WHERE lefts.item_id = po_item.item_id
      AND lefts.purchase_order_id = po_item.purchase_order_id
),
"quantity", (
                SELECT (
                    COALESCE(
                        (
                            SELECT COALESCE(
                                SUM(
                                    CASE
                                        WHEN po_orders.po_order_ids IS NULL THEN po_item_sub.quantity
                                        ELSE 
                                            CASE 
                                                WHEN FIND_IN_SET(po_item_sub.purchase_order_id, po_orders.po_order_ids) = 0 
                                                THEN po_item_sub.quantity
                                                ELSE 0 
                                            END
                                    END
                                ), 0
                            )
                            FROM purchase_order_items po_item_sub
                            WHERE po_item_sub.item_id = po_item.item_id
                              AND po_item_sub.purchase_order_id = po_item.purchase_order_id
                        ), 0
                    )
                    +
                    COALESCE(
                        (
                            SELECT COALESCE(SUM(lefts.total_left_quantity), 0)
                            FROM (
                                SELECT 
                                    po_orders.item_id,
                                    po_orders.purchase_order_id,
                                    SUM(il.quantity) as total_left_quantity
                                FROM item_lefts il
                                INNER JOIN po_orders ON il.item_leftable_id = po_orders.id
                                WHERE il.item_leftable_type = "' . $poClass . '"
                                GROUP BY po_orders.item_id, po_orders.purchase_order_id
                            ) as lefts
                            WHERE lefts.item_id = po_item.item_id
                              AND lefts.purchase_order_id = po_item.purchase_order_id
                        ), 0
                    )
                )
            ),
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
      // ->whereRaw('FIND_IN_SET(poi.purchase_order_id, po_orders.po_order_ids) = 0')  // Exclude po_order_ids
      ->groupBy(
        'poi.item_id',
        'i.name',
        'poi.uom_conversion_id',
        'uc.conversion',
        'poi.base_uom_id',
        'bu.name',
        'poi.uom_id',
        'u.name',
        'i_lefts.total_left_quantity',
        'po_orders.total_po_order_quantity',
      )
      ->paginate(config('common.list_count'));

    foreach ($poOrderItems as $item) {
      $item->purchase_order_details = json_decode($item->purchase_order_details, true);
    }
    return $poOrderItems;
  }

  public function storePoOrderItems($validatedData)
  {
    try {
      $validatedData['created_by'] = UserData()->id;
      $poOrder = PoOrder::create($validatedData);

      if (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) {

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

    return $this->testArrivalList($request);
    $leftsSubquery = DB::table('item_lefts')
      ->join('arrival_items', 'item_lefts.item_leftable_id', '=', 'arrival_items.id')
      ->join('po_orders', 'arrival_items.po_order_id', '=', 'po_orders.id')
      ->select(
        'po_orders.id as po_order_id',
        DB::raw('SUM(item_lefts.quantity) as total_left_quantity'),
        DB::raw('SUM(item_lefts.amount) as total_left_amount')
      )
      ->where('item_lefts.item_leftable_type', '=', 'arrival_item')
      ->groupBy('po_orders.id');



    $poOrders = DB::table('po_orders as poOrder')
      ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
      ->join('purchase_order_items as poi', 'po.id', '=', 'poi.purchase_order_id')
      ->join('items as i', 'poOrder.item_id', '=', 'i.id')
      ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
      ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
      ->leftJoinSub($leftsSubquery, 'item_lefts', function ($join) {
        $join->on('poOrder.id', '=', 'item_lefts.po_order_id');
      })

      ->select(
        'i.id as item_id',
        'i.name as item_name',
        DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('
                  CASE 
                      WHEN SUM(item_lefts.total_left_quantity) > 0 THEN SUM(item_lefts.total_left_quantity) 
                      WHEN COUNT(arrival_items.id) > 0 THEN 0  -- If arrival item exists but no lefts, assume 0
                      ELSE SUM(poOrder.quantity)
                  END as total_quantity'),
        DB::raw('
                  CASE 
                      WHEN SUM(item_lefts.total_left_amount) > 0 THEN SUM(item_lefts.total_left_amount) 
                      WHEN COUNT(arrival_items.id) > 0 THEN 0  -- If arrival item exists but no lefts, assume 0
                      ELSE SUM(poOrder.amount)
                  END as total_amount'),
        'u.id as uom_id',
        'u.name as uom_name',
        'bu.id as base_uom_id',
        'bu.name as base_uom_name',
        'uc.id as uom_conversion_id',
        'uc.conversion as uom_conversion',
        'ip.id as item_price_id',
        'ip.price as item_price'
      )
      ->join('uoms as u', 'poOrder.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poOrder.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poOrder.uom_conversion_unit_id', '=', 'uc.id')
      ->leftJoin('arrival_items', 'poOrder.id', '=', 'arrival_items.po_order_id')
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

  public function testArrivalList($request)
  {
    $leftsSubquery = DB::table('item_lefts')
      ->join('arrival_items', function ($join) {
        $join->on('item_lefts.item_leftable_id', '=', 'arrival_items.id')
          ->where('item_lefts.item_leftable_type', '=', 'arrival_item');
      })
      ->join('po_orders', 'arrival_items.po_order_id', 'po_orders.id')
      ->select(
        'po_orders.item_id',
        DB::raw('SUM(item_lefts.quantity) as left_quantity'),
        DB::raw('GROUP_CONCAT(item_lefts.id SEPARATOR ", ") as item_left_ids'),
      )
      // ->where('po_orders.is_active', 1)
      ->groupBy('po_orders.item_id');

    $arrivalSubQuery = DB::table('arrival_items')
      ->join('po_orders', 'arrival_items.po_order_id', 'po_orders.id')
      ->select(
        'po_orders.item_id',
        DB::raw('SUM(arrival_items.quantity) as arrival_quantity'),
        DB::raw('GROUP_CONCAT(po_orders.purchase_order_id SEPARATOR ",") as po_order_ids'),
      )
      // ->where('po_orders.is_active', 1)
      ->groupBy('po_orders.item_id');



    $poOrders = DB::table('po_orders as poOrder')
      ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
      ->join('purchase_order_items as poi', 'po.id', '=', 'poi.purchase_order_id')
      ->join('items as i', 'poOrder.item_id', '=', 'i.id')
      ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
      ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
      ->leftJoinSub($leftsSubquery, 'item_lefts', function ($join) {
        $join->on('poOrder.item_id', '=', 'item_lefts.item_id');
      })
      ->leftJoinSub($arrivalSubQuery, 'arrival_items', function ($join) {
        $join->on('poOrder.item_id', '=', 'arrival_items.item_id');
      })
      ->select(
        'i.id as item_id',
        'i.name as item_name',
        DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        DB::raw('COALESCE(arrival_items.po_order_ids, "null") as arrival_po_order_ids'),
        DB::raw('COALESCE(SUM(poOrder.quantity), 0) as po_order_quantity'), // Fixed syntax
        DB::raw('COALESCE(item_lefts.left_quantity, 0) as total_left_quantity'), // Default to 0 if no data
        //total_po_order quantity is sum poOrder.quantity related purchase_order_id with not match po_order_ids
        DB::raw('
        COALESCE(
    SUM(
        CASE 
            WHEN arrival_items.po_order_ids IS NULL THEN poOrder.quantity
            WHEN FIND_IN_SET(poOrder.purchase_order_id, arrival_items.po_order_ids) = 0 THEN poOrder.quantity
            ELSE 0 
        END
    ), 0
) AS total_po_order_quantity 
    '),
        DB::raw('
            COALESCE(item_lefts.left_quantity, 0) +
            COALESCE(
                SUM(
                    CASE 
                        WHEN arrival_items.po_order_ids IS NULL THEN poOrder.quantity
                        WHEN FIND_IN_SET(poOrder.purchase_order_id, arrival_items.po_order_ids) = 0 THEN poOrder.quantity
                        ELSE 0 
                    END
                ), 0
            ) AS total_quantity
        '),
        'u.id as uom_id',
        'u.name as uom_name',
        'bu.id as base_uom_id',
        'bu.name as base_uom_name',
        'uc.id as uom_conversion_id',
        'uc.conversion as uom_conversion',
        'ip.id as item_price_id',
        'ip.price as item_price'
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
        'uc.conversion',
        'arrival_items.po_order_ids'
      )
      ->paginate(config('common.list_count'));
    return $poOrders;
  }

  // correct groupBy
  // public function getPoOrderArrivalList(Request $request)
  // {
  //   $poOrders = DB::table('po_orders as poOrder')
  //     ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
  //     ->join('purchase_order_items as poi', 'po.id', '=', 'poi.purchase_order_id')
  //     ->join('items as i', 'poi.item_id', '=', 'i.id')
  //     ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
  //     ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
  //     ->select(
  //       'i.id as item_id',
  //       'i.name as item_name',
  //       DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
  //       DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
  //       DB::raw('SUM(poOrder.quantity) as total_quantity'),
  //       DB::raw('SUM(poOrder.amount) as total_amount'),
  //       'u.id as uom_id',
  //       'u.name as uom_name',
  //       'bu.id as base_uom_id',
  //       'bu.name as base_uom_name',
  //       'uc.id as uom_conversion_id',
  //       'uc.conversion as uom_conversion',
  //       'ip.id as item_price_id',
  //       'ip.price as item_price',
  //     )
  //     ->join('uoms as u', 'poOrder.uom_id', '=', 'u.id')
  //     ->join('uoms as bu', 'poOrder.base_uom_id', '=', 'bu.id')
  //     ->join('uom_conversions as uc', 'poOrder.uom_conversion_unit_id', '=', 'uc.id')
  //     ->groupBy(
  //       'i.id',
  //       'i.name',
  //       'u.id',
  //       'u.name',
  //       'bu.id',
  //       'bu.name',
  //       'ip.id',
  //       'ip.price',
  //       'uc.id',
  //       'uc.conversion',
  //       'po.po_id'
  //     )
  //     ->paginate(config('common.list_count'));
  //   return $poOrders;
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

            $poOrder->quantity = $totalItemLeftQuantity;
            $poOrder->amount = $totalItemLeftAmount;
            $poOrder->base_uom_quantity = $totalItemLeftBaseUomQuantity;
            $poOrder->uom_quantity = $totalItemLeftUomQuantity;
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
          'total_invoice_amount' => $validatedData['amount'],
          'created_by' => UserData()->id,
        ];
        $newPoInvoice = PoInvoice::create($invoiceData);

        $arrivalItemData = [
          'base_uom_id' => $validatedData['base_uom_id'],
          'base_uom_quantity' => $validatedData['base_uom_quantity'],
          'uom_id' => $validatedData['uom_id'],
          'uom_quantity' => $validatedData['uom_quantity'],
          'quantity' => $validatedData['quantity'],
          'amount' => $validatedData['amount'],
          'po_invoice_id' => $newPoInvoice->id,
          'po_order_id' => $validatedData['po_order_id'],
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
            'base_uom_id' => $validatedData['base_uom_id'],
            'base_uom_quantity' => $validatedData['base_uom_quantity'],
            'uom_id' => $validatedData['uom_id'],
            'uom_quantity' => $validatedData['uom_quantity'],
            'quantity' => $validatedData['quantity'],
            'amount' => $validatedData['amount'],
            'po_invoice_id' => $poInvoice->id,
            'po_order_id' => $validatedData['po_order_id'],
            'created_by' => UserData()->id,
          ];
          $arrivalItem = ArrivalItem::create($arrivalItemData);
        } else {

          throw new \Exception('PoInvoice not found for the given ID.');
        }
      }

      $this->storeInventoryLedger($validatedData, $arrivalItem);

      if (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) {
        $poOrder = PoOrder::where('id', $validatedData['po_order_id'])->first();
        if ($poOrder->quantity < $arrivalItem->quantity) {
          ResponseMessage('The arrival  quantity exceeds the available quantity.', 419);
        }

        $remainingQuantity = null;
        if ($poOrder->quantity > $arrivalItem->quantity) {

          $remainingQuantity = $poOrder->quantity - $arrivalItem->quantity;

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

  private function storeInventoryLedger($validatedData, $arrivalItem)
  {

    $inventory = Inventory::where('name', '=', 'Main Inventory')->first();
    if (!$inventory) {
      throw new \Exception('Main Inventory not found.');
    }
    $inventoryLedger = InventoryLedger::create([
      'inventory_id' =>  $inventory->id,
      'date' => now()->format('Y-m-d'),
      'ledgerable_id' => $arrivalItem->id,
      'ledgerable_type' => 'arrival_item',
      'action' => 'in'
    ]);

    $poOrder =  PoOrder::where('id', $validatedData['po_order_id'])->first();
    if ($poOrder) {
      InventoryLedgerItem::create([
        'inventory_ledger_id' => $inventoryLedger->id,
        'item_id' => $poOrder->item_id,
        'quantity' => $validatedData['quantity']
      ]);
    } else {
      throw new \Exception('PoOrder not found for the given ID.');
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
        $totalAvgLeadTime += $avgLeadtime;
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
    $poInvoices = PoInvoice::select([
      'po_invoices.id',
      'po_invoices.invoice_no',
      'po_invoices.date_time',
      'po_invoices.total_invoice_amount',
      'po.supplier_id',
      's.name as supplier_name',
      's.account_id',
      DB::raw('GROUP_CONCAT(DISTINCT i.name SEPARATOR ", ") as item_names'),
      DB::raw('CAST(SUM(ai.quantity) AS SIGNED) as total_invoice_quantity')
    ])
      ->join('arrival_items as ai', 'po_invoices.id', '=', 'ai.po_invoice_id')
      ->join('po_orders as po', 'ai.po_order_id', '=', 'po.id')
      ->join('items as i', 'po.item_id', '=', 'i.id')
      ->join('suppliers as s', 'po.supplier_id', '=', 's.id')
      ->groupBy('po_invoices.id', 'po_invoices.invoice_no', 'po_invoices.date_time', 'po_invoices.total_invoice_amount', 'po.supplier_id', 's.name', 's.account_id')
      ->paginate(config('common.list_count'));
    return PoInvoiceResource::collection($poInvoices);
  }

  public function processInvoiceTransaction($request)
  {
    $poInvoiceId = $request->po_invoice_id;
    $supplierId = $request->supplier_id;
    $supplierAccountId = $request->supplier_account_id;
    $apAmount = $request->ap_amount;
    $cashAccountId = $request->cash_account_id;
    $poInvoice = PoInvoice::find($poInvoiceId);
    if (!$poInvoice) {
      ResponseMessage('Po Invoice not found', 404);
    }
    DB::beginTransaction();
    try {
      $transaction = $this->storeInvoiceTransaction($poInvoice, $request->amount, $cashAccountId);
      if ($apAmount > 0 || ($request->total_invoice_amount < $request->amount)) {
        $this->storeAP($transaction, $apAmount, $supplierId, $supplierAccountId, $cashAccountId);
      }
      DB::commit();
      return ResponseMessage('Transaction created successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
