<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class WithAveragePriceScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
        $builder->from('items')->leftJoin('uom_conversions', function ($join) {
            $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
                ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
                ->where('uom_conversions.is_active', '=', 1);
        })
        ->join('uoms as base_uom', 'items.base_uom_id', 'base_uom.id')
        ->join('uoms as item_uom', 'items.uom_id', 'item_uom.id')
        ->addSelect([
            'items.*',
            'base_uom.name as base_uom_name',
            'item_uom.name as item_uom',
            'uom_conversions.conversion as uom_conversion',
            'uom_conversions.id as uom_conversion_id',
            'average_price' => function ($subQuery) {
                $subQuery->selectRaw('COALESCE(AVG(ip.price), 0)')
                    ->from('supplier_items as si')
                    ->join('item_prices as ip', 'si.id', '=', 'ip.supplier_item_id')
                    ->whereColumn('si.item_id', 'items.id')
                    ->whereRaw('ip.id = (SELECT MAX(sub_ip.id) FROM item_prices sub_ip WHERE sub_ip.supplier_item_id = si.id)');
            }
        ]);
        // ->addSelect(
        //     'items.*',      
        //     'base_uom.name as base_uom_name',
        //     'item_uom.name as item_uom',
        //     'uom_conversions.conversion as uom_conversion',
        //     DB::raw('
        //         CAST((
        //             SELECT COALESCE(AVG(latest_prices.price), 0) 
        //             FROM (
        //                 SELECT ip.price 
        //                 FROM supplier_items si
        //                 JOIN item_prices ip ON si.id = ip.supplier_item_id
        //                 WHERE si.item_id = items.id
        //                 AND ip.id = (
        //                     SELECT MAX(sub_ip.id)
        //                     FROM item_prices sub_ip
        //                     WHERE sub_ip.supplier_item_id = si.id
        //                 )
        //             ) AS latest_prices
        //         ) AS DECIMAL(10,2)) AS average_price
        //     ')
        // );
    }
}
