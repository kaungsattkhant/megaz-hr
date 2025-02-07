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
    $poClass = 'po_order';
    $leftsSubquery = DB::table('item_lefts')
      ->join('po_orders', function ($join) use ($poClass) {
        $join->on('item_lefts.item_leftable_id', '=', 'po_orders.id')
          ->where('item_lefts.item_leftable_type', '=', $poClass);
      })
      ->select(
        'po_orders.item_id',
        // 'po_orders.purchase_order_id',
        DB::raw('SUM(item_lefts.quantity) as total_left_quantity'),
        DB::raw('GROUP_CONCAT(item_lefts.id SEPARATOR ",") as item_left_ids'),
      )
      ->groupBy('po_orders.item_id');

    // return $leftsSubquery->get();
    $poOrderSubquery = DB::table('po_orders')
      ->select(
        'item_id',
        DB::raw('SUM(quantity) as total_po_order_quantity'),
        DB::raw('GROUP_CONCAT(po_orders.purchase_order_id SEPARATOR ",") as po_order_ids')
        // 'brands.id as brand_id',
        // 'brands.name as brand_name'
      )
      ->join('brands', 'po_orders.brand_id', '=', 'brands.id')
      ->groupBy('item_id');
    $quantitySubQuery = '(
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
                        AND po_item_sub.brand_id = po_item.brand_id

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
      )';


    $poOrderItems = DB::table('purchase_order_items as poi')
      ->join('brands as b', 'poi.brand_id', '=', 'b.id')
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
        // 'po_orders.brand_id',
        // 'po_orders.brand_name',
        DB::raw('GROUP_CONCAT(DISTINCT b.id SEPARATOR ", ") as brand_ids'),
        DB::raw('GROUP_CONCAT(DISTINCT b.name SEPARATOR ", ") as brand_name'),

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
        DB::raw('COALESCE(i_lefts.total_left_quantity, 0) as left_quantity'),
        DB::raw('COALESCE(i_lefts.item_left_ids, "null") as item_left_ids'),
        DB::raw('GROUP_CONCAT(DISTINCT poi.purchase_order_id SEPARATOR ", ") as po_ids'),
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
        DB::raw('
    CASE 
        WHEN (
            (COALESCE(i_lefts.total_left_quantity, 0) + 
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
            ) 
        ) < uc.conversion THEN 0 
        ELSE FLOOR((
            (COALESCE(i_lefts.total_left_quantity, 0) + 
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
            )) / uc.conversion) 
    END AS total_base_uom_quantity
'),
        DB::raw('
    CASE 
        WHEN (
            (COALESCE(i_lefts.total_left_quantity, 0) + 
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
            ) 
        ) < uc.conversion THEN 
            (COALESCE(i_lefts.total_left_quantity, 0) + 
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
            ) 
        ELSE 
            (COALESCE(i_lefts.total_left_quantity, 0) + 
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
            ) % uc.conversion 
    END AS total_uom_quantity
'),
        DB::raw('(
            SELECT JSON_ARRAYAGG(
                JSON_OBJECT(
                    "id", po_item.purchase_order_id,
                    "brand_id", po_item.brand_id,
                    "brand_name",brand.name,
                    "item_name", i.name,
                    "item_id", i.id,
                    "amount", po_item.amount,
                    "base_uom_id", po_item.base_uom_id,
                    "base_uom_name",base_uom.name,
                    "uom_name",uom.name,
                    "uom_id", po_item.uom_id,
                    "uom_conversion_id", po_item.uom_conversion_id,
                    "uom_conversion", uc.conversion,
                    "base_uom_quantity", 
                  CASE 
                      WHEN (
                          ' . $quantitySubQuery . '
                      ) < uc.conversion THEN 0 
                      ELSE FLOOR((
                          ' . $quantitySubQuery . '
                      ) / uc.conversion) 
                  END,
                  "uom_quantity", 
                  CASE 
                      WHEN (
                          ' . $quantitySubQuery . '
                      ) < uc.conversion THEN (
                          ' . $quantitySubQuery . '
                      ) 
                      ELSE (
                          ' . $quantitySubQuery . '
                      ) % uc.conversion 
                  END,
                    "item_left_id", (
      SELECT il.id
      FROM item_lefts il
      INNER JOIN po_orders ON il.item_leftable_id = po_orders.id
      WHERE il.item_leftable_type = "' . $poClass . '"
        AND po_orders.item_id = po_item.item_id
        AND po_orders.purchase_order_id = po_item.purchase_order_id
      LIMIT 1
  ),
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
        AND po_item_sub.brand_id = po_item.brand_id

              ),
                  "total_po_order_quantity", (
      SELECT COALESCE(SUM(po_order_sub.quantity), 0)
      FROM po_orders po_order_sub
      WHERE po_order_sub.item_id = po_item.item_id
        AND po_order_sub.purchase_order_id = po_item.purchase_order_id
        AND po_order_sub.brand_id = po_item.brand_id
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
  "quantity",' . $quantitySubQuery . ',
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
            INNER JOIN uoms uom ON po_item.uom_id = uom.id
            INNER JOIN uoms base_uom ON po_item.base_uom_id = base_uom.id
            INNER JOIN purchase_orders po ON po_item.purchase_order_id = po.id
            INNER JOIN brands brand ON po_item.brand_id = brand.id
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
        // 'po_orders.brand_id',
        // 'po_orders.brand_name',
        'poi.uom_conversion_id',
        'uc.conversion',
        'poi.base_uom_id',
        'bu.name',
        'poi.uom_id',
        'u.name',
        'i_lefts.total_left_quantity',

        // 'po_orders.total_po_order_quantity',
        'po_orders.po_order_ids',
        'i_lefts.item_left_ids',
      )
      ->having('total_quantity', '>', 0) // Filter out records where quantity <= 0
      ->paginate(config('common.list_count'));

    // return $poOrderItems;
    foreach ($poOrderItems as $item) {
      // $item->purchase_order_details = json_decode($item->purchase_order_details, true);
      $details = collect(json_decode($item->purchase_order_details, true)); // Convert to a Collection

      // Filter the details where quantity > 0
      $poNumbers = []; // Initialize an array to store po_ids

      // Filter the details and collect po_ids
      $filteredDetails = $details->filter(function ($detail) use (&$poNumbers) {
        if (isset($detail['quantity']) && $detail['quantity'] > 0) {
          // Collect po_id if it meets the criteria

          if (isset($detail['purchase_order']['po_id'])) {
            $poNumbers[] = $detail['purchase_order']['po_id'];
          }
          return true; // Keep this detail
        }
        return false; // Filter out this detail
      });
      // Assign the filtered details back to the item
      $item->purchase_order_details = $filteredDetails->values()->toArray();

      $item->po_numbers = implode(',', $poNumbers);
    }
    return $poOrderItems;
  }

  public function storePoOrderItems($validatedData)
  {
    DB::beginTransaction();
    try {
      $validatedData['created_by'] = UserData()->id;
      $poOrder = PoOrder::create($validatedData);
      if (isset($validatedData['item_left_id']) && $validatedData['item_left_id'] != "null") {
        $itemLeft = ItemLeft::where('id', $validatedData['item_left_id'])->first();
        if ($itemLeft && (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) && $itemLeft->purchase_order_id == $poOrder->purchase_order_id) {

          if ($itemLeft->quantity < $poOrder->quantity) {
            ResponseMessage('The order quantity exceeds the available quantity.', 419);
          }
          $itemLeft->base_uom_quantity -= $validatedData['base_uom_quantity'];
          $itemLeft->uom_quantity -= $validatedData['uom_quantity'];
          $itemLeft->quantity -= $poOrder->quantity;
          $itemLeft->amount -= $validatedData['amount'];
          $itemLeft->save();
        } else {
          $itemLeft->base_uom_quantity = 0;
          $itemLeft->uom_quantity = 0;
          $itemLeft->quantity = 0;
          $itemLeft->amount = 0;
          $itemLeft->save();
        }
      } else {
        if (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1 && isset($validatedData['item_left_id']) && $validatedData['item_left_id'] == "null") {

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
              'amount' => $purchaseOrderItem->amount - $validatedData['amount'],
              'unit_price' => $validatedData['unit_price'],
              'created_by' => UserData()->id,
              'item_leftable_id' => $poOrder->id,
              'item_leftable_type' => $validatedData['item_leftable_type'],
              'purchase_order_id' => $validatedData['purchase_order_id'],
              'brand_id' => $validatedData['brand_id']
            ];
            ItemLeft::create($itemLeftData);
          }
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
    $leftsSubquery = DB::table('item_lefts')
      ->join('arrival_items', function ($join) {
        $join
          ->on('item_lefts.item_leftable_id', '=', 'arrival_items.id')
          ->where('item_lefts.item_leftable_type', '=', 'arrival_item');
      })
      ->select(
        'arrival_items.item_id',
        DB::raw('SUM(item_lefts.quantity) as left_quantity'),
        DB::raw('SUM(item_lefts.amount) as left_amount'),
        DB::raw('GROUP_CONCAT(item_lefts.id SEPARATOR ", ") as item_left_ids')
      )
      ->groupBy(
        'arrival_items.item_id',
      );

    $arrivalSubQuery = DB::table('arrival_items')
      ->select(
        'arrival_items.item_id',
        DB::raw('SUM(arrival_items.quantity) as arrival_quantity'),
        DB::raw('SUM(arrival_items.amount) as arrival_amount'),
        DB::raw('GROUP_CONCAT(arrival_items.purchase_order_id SEPARATOR ",") as po_order_ids')
      )
      ->groupBy(
        'arrival_items.item_id',
      );

    // Main PO Orders Query
    $poOrders = DB::table('po_orders as poOrder')
      ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
      ->join('items as i', 'poOrder.item_id', '=', 'i.id')
      ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
      ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
      ->leftJoinSub($leftsSubquery, 'item_lefts', function ($join) {
        $join->on('poOrder.item_id', '=', 'item_lefts.item_id');
        // ->on('poOrder.purchase_order_id', '=', 'item_lefts.po_order_id');
        // ->on('poOrder.brand_id', '=', 'item_lefts.brand_id');
      })
      ->leftJoinSub($arrivalSubQuery, 'arrival_items', function ($join) {
        $join->on('poOrder.item_id', '=', 'arrival_items.item_id');
        // ->on('poOrder.purchase_order_id', '=', 'arrival_items.purchase_order_id');
        // ->on('poOrder.brand_id', '=', 'arrival_items.brand_id');
      })
      // ->join('brands as b', 'poOrder.brand_id', '=', 'b.id')
      ->select(
        'i.id as item_id',
        'i.name as item_name',
        'u.name as uom_name',
        'bu.name as base_uom_name',
        DB::raw('COALESCE(SUM(poOrder.quantity), 0) AS total_po_order_quantity'),
        DB::raw('COALESCE(SUM(poOrder.amount), 0) AS total_po_order_amount'),
        DB::raw('GROUP_CONCAT(DISTINCT s.name SEPARATOR ", ") as supplier_name'),
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        // DB::raw('COALESCE(arrival_items.po_order_ids, "null") as arrival_po_order_ids'),
        DB::raw('COALESCE(arrival_items.arrival_quantity, 0) as total_arrival_quantity'),
        DB::raw('COALESCE(item_lefts.left_quantity, 0) as total_left_quantity'),
        DB::raw('COALESCE(item_lefts.item_left_ids, "null") as item_left_id'),
        DB::raw('COUNT(DISTINCT poOrder.id) AS row_count'),
        DB::raw('COALESCE(SUM(poOrder.amount), 0) - COALESCE(arrival_items.arrival_amount, 0) AS total_amount'),
        DB::raw('COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0) AS total_quantity'),
        DB::raw('COALESCE(item_lefts.left_amount, 0) as total_left_amount'),
        DB::raw('COALESCE(arrival_items.arrival_amount, 0) as total_arrival_amount'),
        DB::raw('
    CASE 
        WHEN (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0)) < uc.conversion 
        THEN 0 
        ELSE FLOOR((COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0)) / uc.conversion) 
    END AS total_base_uom_quantity
'),

        DB::raw('
    CASE 
        WHEN (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0)) < uc.conversion 
        THEN (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
        ELSE (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0)) % uc.conversion 
    END AS total_uom_quantity
'),
        // DB::raw('GROUP_CONCAT(DISTINCT b.name SEPARATOR ", ") as brands')
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
        // 'ip.id',
        // 'ip.price',
        'uc.id',
        'uc.conversion',
        'item_lefts.left_quantity',
        'item_lefts.left_amount',
        'arrival_items.arrival_amount',
        'arrival_items.arrival_quantity',
        'item_lefts.item_left_ids',
        // 'arrival_items.po_order_ids',
        // 'poOrder.brand_id',
      )
      ->paginate(config('common.list_count'));

    return $poOrders;
  }


  public function getPoOrderArrivalListByItemId($itemId)
  {

    $leftsSubquery = DB::table('item_lefts')
      // ->join('purchase_orders','item_lefts.purchase_order_id','purchase_orders.id')
      ->join('arrival_items', function ($join) {
        $join->on('item_lefts.item_leftable_id', '=', 'arrival_items.id')
          ->where('item_lefts.item_leftable_type', '=', 'arrival_item');
      })
      // ->join('po_orders', 'arrival_items.po_order_id', 'po_orders.id')
      ->select(
        'arrival_items.item_id',
        'arrival_items.purchase_order_id',
        'arrival_items.brand_id',
        // 'po_orders.id as po_order_id',
        DB::raw('SUM(item_lefts.quantity) as left_quantity'),
        DB::raw('SUM(item_lefts.amount) as left_amount'),
        DB::raw('GROUP_CONCAT(item_lefts.id SEPARATOR ", ") as item_left_ids'),
      )
      ->where('arrival_items.item_id', $itemId)
      ->groupBy('arrival_items.item_id', 'arrival_items.purchase_order_id', 'arrival_items.brand_id');

    $arrivalSubQuery = DB::table('arrival_items')
      // ->join('po_orders', 'arrival_items.po_order_id', 'po_orders.id')
      ->select(
        'arrival_items.item_id',
        'arrival_items.purchase_order_id',
        'arrival_items.brand_id',
        DB::raw('SUM(arrival_items.quantity) as arrival_quantity'),
        DB::raw('SUM(arrival_items.amount) as arrival_amount'),
        // DB::raw('GROUP_CONCAT(arrival_items.purchase_order_id SEPARATOR ",") as po_order_ids'),
      )
      ->where('arrival_items.item_id', $itemId)
      ->groupBy('arrival_items.item_id', 'arrival_items.purchase_order_id', 'arrival_items.brand_id');

    // return $arrivalSubQuery->get();

    $poOrders = DB::table('po_orders as poOrder')
      ->join('purchase_orders as po', 'poOrder.purchase_order_id', '=', 'po.id')
      ->join('items as i', 'poOrder.item_id', '=', 'i.id')
      ->join('suppliers as s', 'poOrder.supplier_id', '=', 's.id')
      ->join('item_prices as ip', 'poOrder.item_price_id', '=', 'ip.id')
      ->leftJoinSub($leftsSubquery, 'item_lefts', function ($join) {
        $join->on('poOrder.item_id', '=', 'item_lefts.item_id')
          ->on('item_lefts.purchase_order_id', '=', 'poOrder.purchase_order_id')
          ->on('poOrder.brand_id', '=', 'item_lefts.brand_id');
      })
      ->leftJoinSub($arrivalSubQuery, 'arrival_items', function ($join) {
        $join->on('poOrder.item_id', '=', 'arrival_items.item_id')
          ->on('arrival_items.purchase_order_id', '=', 'poOrder.purchase_order_id')
          ->on('poOrder.brand_id', '=', 'arrival_items.brand_id');
      })->join('brands as b', 'poOrder.brand_id', '=', 'b.id')
      ->select(
        'poOrder.purchase_order_id',
        'item_lefts.item_left_ids as item_left_id',
        'po.po_id',
        'i.id as item_id',
        'i.name as item_name',
        's.id as supplier_id',
        's.name as supplier_name',
        'poOrder.unit_price',
        DB::raw('GROUP_CONCAT(DISTINCT po.po_id SEPARATOR ", ") as po_numbers'),
        // DB::raw('COALESCE(arrival_items.po_order_ids, "null") as arrival_po_order_ids'),
        // DB::raw('COALESCE(item_lefts.left_quantity, 0) as total_left_quantity'),
        // DB::raw('COALESCE(item_lefts.left_amount, 0) as total_left_amount'),
        DB::raw('COALESCE(arrival_items.arrival_amount, 0) as total_arrival_amount'),
        DB::raw('COALESCE(arrival_items.arrival_quantity, 0) as total_arrival_quantity'), // Default to 0 if no data
        DB::raw('SUM(poOrder.amount) as total_po_order_amount'),
        DB::raw('
        COALESCE(SUM(poOrder.quantity), 0) -
        COALESCE(arrival_items.arrival_quantity, 0)
         AS quantity
    '),
        DB::raw('
                COALESCE(SUM(poOrder.amount), 0) - COALESCE(arrival_items.arrival_amount, 0) 
                AS amount
            '),
        DB::raw('COALESCE(SUM(poOrder.quantity), 0) AS total_po_order_quantity'),
        DB::raw('
          COALESCE(SUM(poOrder.quantity), 0) -
          COALESCE(arrival_items.arrival_quantity, 0)
           AS quantity
      '),

        DB::raw('
        CASE 
            WHEN (
               
                (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
            ) < uc.conversion THEN 0 
            ELSE FLOOR((
                
                (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
            ) / uc.conversion) 
        END AS base_uom_quantity
    '),
        DB::raw('
    CASE 
        WHEN (
            (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
        ) < uc.conversion THEN (
         
            (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
        ) 
        ELSE (
            (COALESCE(SUM(poOrder.quantity), 0) - COALESCE(arrival_items.arrival_quantity, 0))
        ) % uc.conversion 
    END AS uom_quantity
'),
        'u.id as uom_id',
        'u.name as uom_name',
        'bu.id as base_uom_id',
        'bu.name as base_uom_name',
        'uc.id as uom_conversion_id',
        'uc.conversion as uom_conversion',
        'ip.id as item_price_id',
        'ip.price as item_price',
        DB::raw('GROUP_CONCAT(DISTINCT b.name SEPARATOR ", ") as brand'),
        'b.id as brand_id'
      )
      ->join('uoms as u', 'poOrder.uom_id', '=', 'u.id')
      ->join('uoms as bu', 'poOrder.base_uom_id', '=', 'bu.id')
      ->join('uom_conversions as uc', 'poOrder.uom_conversion_unit_id', '=', 'uc.id')
      ->groupBy(
        'i.id',
        'poOrder.purchase_order_id', // Grouping by purchase_order_id
        's.id',
        'i.name',
        'u.id',
        'u.name',
        'bu.id',
        'bu.name',
        'ip.id',
        'ip.price',
        'poOrder.unit_price',
        'uc.id',
        'uc.conversion',
        // 'item_lefts.left_quantity',
        // 'item_lefts.left_amount',
        // 'arrival_items.po_order_ids',
        'arrival_items.arrival_amount',
        's.name',
        'po.po_id',
        'item_lefts.item_left_ids',
        'arrival_items.arrival_quantity',
        'arrival_items.arrival_amount',
        'b.name',
        'b.id'
      )
      ->where('poOrder.item_id', $itemId)
      ->having('quantity', '>', 0)
      // ->having('total_amount', '>', 0)
      ->get();
    return $poOrders;
  }

  public function getInvoiceBySupplier($supplierId)
  {
    $invoices = PoInvoice::with(['arrivalItems'])
      ->whereHas('arrivalItems', function ($q) use ($supplierId) {
        $q->where('supplier_id', $supplierId);
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
          'uom_conversion_unit_id' => $validatedData['uom_conversion_unit_id'],
          'quantity' => $validatedData['quantity'],
          'amount' => $validatedData['amount'],
          'unit_price' => $validatedData['unit_price'],
          'po_invoice_id' => $newPoInvoice->id,
          'item_id' => $validatedData['item_id'],
          'supplier_id' => $validatedData['supplier_id'],
          'purchase_order_id' => $validatedData['purchase_order_id'],
          'created_by' => UserData()->id,
          'brand_id' => $validatedData['brand_id']
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
            'uom_conversion_unit_id' => $validatedData['uom_conversion_unit_id'],
            'quantity' => $validatedData['quantity'],
            'amount' => $validatedData['amount'],
            'unit_price' => $validatedData['unit_price'],
            'po_invoice_id' => $poInvoice->id,
            'item_id' => $validatedData['item_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'purchase_order_id' => $validatedData['purchase_order_id'],
            'created_by' => UserData()->id,
            'brand_id' => $validatedData['brand_id']
          ];
          $arrivalItem = ArrivalItem::create($arrivalItemData);
        } else {

          throw new \Exception('PoInvoice not found for the given ID.');
        }
      }

      $this->storeInventoryLedger($validatedData, $arrivalItem);



      if (isset($validatedData['item_left_id']) && $validatedData['item_left_id'] != "null") {
        $itemLeft = ItemLeft::where('id', $validatedData['item_left_id'])->first();
        if ($itemLeft && (isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1) && $itemLeft->purchase_order_id == $arrivalItem->purchase_order_id) {

          if ($itemLeft->quantity < $arrivalItem->quantity) {
            ResponseMessage('The arrival quantity exceeds the available quantity.', 419);
          }
          $itemLeft->base_uom_quantity -= $validatedData['base_uom_quantity'];
          $itemLeft->uom_quantity -= $validatedData['uom_quantity'];
          $itemLeft->quantity -= $arrivalItem->quantity;
          $itemLeft->amount -= $validatedData['amount'];
          $itemLeft->save();
        } else {
          $itemLeft->base_uom_quantity = 0;
          $itemLeft->uom_quantity = 0;
          $itemLeft->quantity = 0;
          $itemLeft->amount = 0;
          $itemLeft->save();
        }
      } else {
        if (
          isset($validatedData['later_buy']) && $validatedData['later_buy'] == 1 &&
          isset($validatedData['item_left_id']) && $validatedData['item_left_id'] == "null"
        ) {
          $poOrder = PoOrder::where('purchase_order_id', $validatedData['purchase_order_id'])
            ->where('item_id', $validatedData['item_id'])->first();

          if ($poOrder->quantity < $arrivalItem->quantity) {
            ResponseMessage('The arrival quantity exceeds the available quantity.', 419);
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
              'amount' => $poOrder->amount - $validatedData['amount'],
              'unit_price' => $validatedData['unit_price'],
              'created_by' => UserData()->id,
              'item_leftable_id' => $arrivalItem->id,
              'item_leftable_type' => $validatedData['item_leftable_type'],
              'purchase_order_id' => $poOrder->purchase_order_id,
              'brand_id' => $validatedData['brand_id']
            ];

            ItemLeft::create($itemLeftData);
          }
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
      ResponseMessage('Main Inventory not found.', 404);
    }
    $inventoryLedger = InventoryLedger::create([
      'inventory_id' => $inventory->id,
      'date' => now()->format('Y-m-d'),
      'ledgerable_id' => $arrivalItem->id,
      'ledgerable_type' => 'arrival_item',
      'action' => 'in'
    ]);

    InventoryLedgerItem::create([
      'inventory_ledger_id' => $inventoryLedger->id,
      'item_id' => $arrivalItem->item_id,
      'quantity' => $validatedData['quantity']
    ]);
  }

  public function getSupplierLeadTime($supplierId)
  {
    $result = null;
    $totalAvgLeadTime = 0;
    $itemsData = [];
    $itemLeadTimes = [];

    $poOrderlist = PoOrder::with(['purchaseOrder', 'item', 'arrivalItems', 'supplier'])
      ->where('supplier_id', $supplierId)
      ->get();

    if ($poOrderlist->isEmpty()) {
      ResponseMessage('No purchase orders found for this supplier.', 404);
    }

    $purchaseOrderIds = $poOrderlist->pluck('purchase_order_id');

    $totalArrivalItemCount = ArrivalItem::whereIn('purchase_order_id', $purchaseOrderIds)
      ->where('supplier_id', $supplierId)
      ->count();

    foreach ($poOrderlist as $poOrder) {
      $orderTime = new \Carbon\Carbon($poOrder->created_at);

      foreach ($poOrder->arrivalItems as $arrival) {
        $arrivalTime = new \Carbon\Carbon($arrival->created_at);


        $leadTime = abs($arrivalTime->diffInSeconds($orderTime));
        $avgLeadtime = $leadTime / $totalArrivalItemCount;
        $totalAvgLeadTime += $avgLeadtime;
        // Group lead times by item_id (handle duplicates)
        if (!isset($itemLeadTimes[$arrival->item->id])) {
          $itemLeadTimes[$arrival->item->id] = [
            'total_lead_time' => 0,
            'count' => 0
          ];
        }

        // Add lead time and increase count for unique items
        $itemLeadTimes[$arrival->item->id]['total_lead_time'] += $leadTime;
        $itemLeadTimes[$arrival->item->id]['count']++;
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
      'supplier_name' => $poOrder->supplier->name ?? 'null',
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
      'ai.supplier_id',
      's.name as supplier_name',
      'i.name as i_name',
      's.account_id',
      'po_invoices.is_complete',
      'po_invoices.completed_at',
      DB::raw('GROUP_CONCAT(DISTINCT b.name SEPARATOR ", ") as brands'),
      DB::raw('GROUP_CONCAT(DISTINCT i.name SEPARATOR ", ") as item_names'),
      DB::raw('CAST(SUM(ai.quantity) AS SIGNED) as total_invoice_quantity')
    ])
      ->join('arrival_items as ai', 'po_invoices.id', '=', 'ai.po_invoice_id')
      ->join('suppliers as s', 'ai.supplier_id', '=', 's.id')
      ->join('items as i', 'ai.item_id', '=', 'i.id')
      ->join('brands as b', 'ai.brand_id', '=', 'b.id')
      ->groupBy(
        'po_invoices.id',
        'po_invoices.invoice_no',
        'po_invoices.date_time',
        'po_invoices.total_invoice_amount',
        'ai.supplier_id',
        's.name',
        'i.name',
        's.account_id',
        'po_invoices.is_complete',
        'po_invoices.completed_at',
      )
      ->where('is_complete', 0)
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
    if ($poInvoice->is_complete) {
      ResponseMessage('Po Invoice is already completed', 404);
    }
    DB::beginTransaction();
    try {
      $transaction = $this->storeInvoiceTransaction($poInvoice, $request->amount, $cashAccountId);
      if ($apAmount > 0 || ($request->total_invoice_amount < $request->amount)) {
        $this->storeAP($transaction, $apAmount, $supplierId, $supplierAccountId, $cashAccountId);
      }
      $poInvoice->is_complete = 1;
      $poInvoice->completed_at = now();
      $poInvoice->save();
      DB::commit();
      return ResponseMessage('Transaction created successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
