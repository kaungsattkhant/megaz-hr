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
        DB::raw('SUM(poi.quantity) as total_quantity'),
        DB::raw('SUM(poi.amount) as total_amount'),
        DB::raw('GROUP_CONCAT(po.po_id SEPARATOR ", ") as po_numbers'),
        'i.name as item_name',
        'u.name as uom_name',
        'bu.name as base_uom_name',
        'uc.conversion as uom_conversion',
        'poi.base_uom_id',
        'poi.base_uom_quantity',
        'poi.uom_id',
        'poi.uom_quantity',
        'poi.uom_conversion_id'
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
      ->get();
    $poOrderItemsWithDetails = $poOrderItems->map(function ($item) {
      $item->purchase_orders = PurchaseOrderItem::with(['item', 'uom', 'baseUom', 'uomConversion', 'purchase_order'])
        ->where('item_id', $item->item_id)
        ->where('is_md_checked', true)
        ->whereHas('purchase_order', function ($query) {
          $query->where('status', 'md_checked');
        })
        ->get();
      return $item;
    });
    return   $poOrderItemsWithDetails;
  }


  // public function getPoOrderItemsById(Request $request, $itemId)
  // {
  //   $poOrderItem = PurchaseOrderItem::with(['item', 'uom', 'baseUom', 'uomConversion', 'purchase_order'])->where('item_id', $itemId)
  //     ->where('is_md_checked', true)
  //     ->whereHas('purchase_order', function ($query) {
  //       $query->where('status', 'md_checked');
  //     })->get();
  //   return  $poOrderItem;
  // }
}
