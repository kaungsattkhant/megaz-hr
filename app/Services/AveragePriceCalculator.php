<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class AveragePriceCalculator
{
    public function getAveragePriceForItem($itemId)
    {
        return DB::table('supplier_items')
        ->join('item_prices', 'supplier_items.id', '=', 'item_prices.supplier_item_id')
        ->where('supplier_items.item_id', $itemId)  // Replace `$itemId` with the actual item ID
        ->join(DB::raw('(SELECT supplier_item_id, MAX(id) AS latest_price_id
                         FROM item_prices
                         GROUP BY supplier_item_id) AS latest_prices'),
               'item_prices.id', '=', 'latest_prices.latest_price_id')
        ->selectRaw('CAST(AVG(item_prices.price) AS DECIMAL(10,2)) AS average_price')
        ->value('average_price');
    }
}