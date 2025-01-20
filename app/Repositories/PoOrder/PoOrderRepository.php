<?php

namespace App\Repositories\PoOrder;

use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

class PoOrderRepository implements PoOrderRepositoryInterface
{

  public function getPoOrderItems(Request $request)
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
    // $poOrderItems->getCollection()->transform(function ($item) {
    //   if ($item->purchase_order_details) {
    //     $item->purchase_order_details = json_decode('[' . $item->purchase_order_details . ']');
    //   }
    //   return $item;
    // });
    foreach ($poOrderItems->items() as $item) {
      if ($item->purchase_order_details) {
        $item->purchase_order_details = json_decode('[' . $item->purchase_order_details . ']');
      }
    }

    return $poOrderItems;
  }
}
