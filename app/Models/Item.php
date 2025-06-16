<?php

namespace App\Models;

use App\Models\ItemType;
use App\Models\ItemPrice;
use App\Models\UomConversion;
use App\Models\MrpRawMaterial;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\WithAveragePriceScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{

    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'item_type_id',
        'base_uom_id',
        'uom_id',
        'is_active',
        'min_holding_base_uom_quantity',
        'min_holding_uom_quantity',
        'minimum_holding_amount',
        'limitation_type',
        'amount',
        'max_limit_base_uom_quantity',
        'max_limit_uom_quantity',
    ];



    protected $with = ['brands'];

    protected static function boot()
    {
        parent::boot();

        // Apply the global scope
        static::addGlobalScope(new WithAveragePriceScope);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function PurchaseOrderItem()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function uoms()
    {
        return $this->belongsToMany(Uom::class, 'items_uoms', 'item_id', 'uom_id');
    }

    public function baseUoms()
    {
        return $this->belongsToMany(Uom::class, 'items_uoms', 'item_id', 'base_uom_id');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_item', 'item_id', 'brand_id');
    }

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function item_prices()
    {
        return $this->hasOne(ItemPrice::class)->latest('created_at');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_items');
    }
    public function uomConversion()
    {
        return $this->hasOneThrough(
            UomConversion::class,
            ItemPrice::class,
            'item_id', // Foreign key on ItemPrice table
            'conversion_unit_id', // Foreign key on UomConversion table
            'id', // Local key on Item table
            'base_uom_id' // Local key on ItemPrice table
        );
    }
    public function scopeWithUomConversion($query)
    {
        $query->leftJoin('uom_conversions', function ($join) {
            $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
                ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
                ->where('uom_conversions.is_active', '=', 1);
        })
            ->join('uoms as base_uom', 'items.base_uom_id', 'base_uom.id')
            ->join('uoms as item_uom', 'items.uom_id', 'item_uom.id')
            ->select(
                'items.*',
                'base_uom.name as base_uom_name',
                'item_uom.name as item_uom',
                'uom_conversions.conversion as uom_conversion',
                DB::raw('
                                            CAST((
                                                SELECT COALESCE(AVG(latest_prices.price), 0) 
                                                FROM (
                                                    SELECT ip.price 
                                                    FROM supplier_items si
                                                    JOIN item_prices ip ON si.id = ip.supplier_item_id
                                                    WHERE si.item_id = items.id
                                                    AND ip.id = (
                                                        SELECT MAX(sub_ip.id)
                                                        FROM item_prices sub_ip
                                                        WHERE sub_ip.supplier_item_id = si.id
                                                    )
                                                ) AS latest_prices
                                            ) AS DECIMAL(10,2)) AS average_price
                                        ')
            );
    }




    public function getItemPriceWithConversionAttribute()
    {
        // Calculate the item price with conversion
        $itemPrice = $this->item_prices->price; // Price of the item
        $conversionRate = $this->uomConversion->conversion; // Conversion rate
        return $itemPrice * $conversionRate;
    }

    public function supplier_items()
    {
        return $this->hasMany(SupplierItem::class);
    }

    public function scopeWithAveragePrice($query)
    {
        // $query->leftJoin('uom_conversions', function ($join) {
        //     $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
        //         ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
        //         ->where('uom_conversions.is_active', '=', 1);
        // })
        //     ->join('uoms as base_uom', 'items.base_uom_id', 'base_uom.id')
        //     ->join('uoms as item_uom', 'items.uom_id', 'item_uom.id')
        //     ->select(
        //         'items.*',
        //         'base_uom.name as base_uom_name',
        //         'item_uom.name as item_uom',
        //         'uom_conversions.conversion as uom_conversion',
        //         DB::raw('
        //                                     CAST((
        //                                         SELECT COALESCE(AVG(latest_prices.price), 0) 
        //                                         FROM (
        //                                             SELECT ip.price 
        //                                             FROM supplier_items si
        //                                             JOIN item_prices ip ON si.id = ip.supplier_item_id
        //                                             WHERE si.item_id = items.id
        //                                             AND ip.id = (
        //                                                 SELECT MAX(sub_ip.id)
        //                                                 FROM item_prices sub_ip
        //                                                 WHERE sub_ip.supplier_item_id = si.id
        //                                             )
        //                                         ) AS latest_prices
        //                                     ) AS DECIMAL(10,2)) AS average_price
        //                                 ')
        //     );

        $query->leftJoin('uom_conversions', function ($join) {
            $join->on('uom_conversions.base_unit_id', '=', 'items.base_uom_id')
                ->whereColumn('uom_conversions.conversion_unit_id', '=', 'items.uom_id')
                ->where('uom_conversions.is_active', '=', 1);
        })
            ->join('uoms as base_uom', 'items.base_uom_id', 'base_uom.id')
            ->join('uoms as item_uom', 'items.uom_id', 'item_uom.id')
            ->addSelect(
                'items.*',
                'base_uom.name as base_uom_name',
                'item_uom.name as item_uom',
                'uom_conversions.conversion as uom_conversion',
                DB::raw('
                CAST((
                    SELECT COALESCE(AVG(latest_prices.price), 0) 
                    FROM (
                        SELECT ip.price 
                        FROM supplier_items si
                        JOIN item_prices ip ON si.id = ip.supplier_item_id
                        WHERE si.item_id = items.id
                        AND ip.id = (
                            SELECT MAX(sub_ip.id)
                            FROM item_prices sub_ip
                            WHERE sub_ip.supplier_item_id = si.id
                        )
                    ) AS latest_prices
                ) AS DECIMAL(10,2)) AS average_price
            ')
            );
    }

    public function balance()
    {
        return $this->hasOne(InventoryLedgerItem::class, 'item_id')
            ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
            ->select(
                'inventory_ledger_items.item_id',
                DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" THEN inventory_ledger_items.quantity ELSE 0 END) as in_balance'),
                DB::raw('SUM(CASE WHEN inventory_ledgers.action = "out" THEN inventory_ledger_items.quantity ELSE 0 END) as out_balance'),
                DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" THEN inventory_ledger_items.quantity ELSE 0 END) -
                     SUM(CASE WHEN inventory_ledgers.action = "out" THEN inventory_ledger_items.quantity ELSE 0 END) as closing_balance')
            )
            ->groupBy('inventory_ledger_items.item_id');
    }

    // public function scopeWithBalanceDetails(Builder $query, $inventoryId, $itemId, $fromDate = null, $toDate = null)
    // {
    //     return $query
    //         ->join('inventory_ledger_items', 'items.id', '=', 'inventory_ledger_items.item_id')
    //         ->joinSub(
    //             DB::table('supplier_items as si')
    //                 ->join('item_prices as ip', 'si.id', '=', 'ip.supplier_item_id')
    //                 ->select(
    //                     'si.item_id',
    //                     DB::raw('CAST(AVG(ip.price) AS DECIMAL(10,2)) AS average_price'),
    //                     'ip.base_uom_id'
    //                 )
    //                 ->whereIn(
    //                     'ip.id',
    //                     DB::table('item_prices as sub_ip')
    //                         ->select(DB::raw('MAX(sub_ip.id)'))
    //                         ->whereColumn('sub_ip.supplier_item_id', 'si.id')
    //                         ->groupBy('sub_ip.supplier_item_id')
    //                 )
    //                 ->groupBy('si.item_id', 'ip.base_uom_id'),
    //             'latest_prices',
    //             'items.id',
    //             'latest_prices.item_id'
    //         )
    //         ->join('uoms as item_uom', 'items.uom_id', '=', 'item_uom.id')
    //         ->join('uoms as base_uom', 'items.base_uom_id', '=', 'base_uom.id')
    //         ->join('inventory_ledgers', 'inventory_ledger_items.inventory_ledger_id', '=', 'inventory_ledgers.id')
    //         ->join('uom_conversions', function ($join) {
    //             $join->on('latest_prices.base_uom_id', '=', 'uom_conversions.base_unit_id')
    //                 ->on('items.uom_id', '=', 'uom_conversions.conversion_unit_id')
    //                 ->where('uom_conversions.is_active', 1);
    //         })
    //         ->select(
    //             'items.name',
    //             'inventory_ledger_items.item_id',
    //             'items.uom_id as item_uom_id',
    //             'latest_prices.base_uom_id as base_uom_id',
    //             'latest_prices.average_price as price',
    //             'items.base_uom_id as base_unit_id',
    //             'item_uom.name as conversion_uom_name',
    //             'base_uom.name as base_uom_name',
    //             'uom_conversions.conversion as conversion',
    //             'uom_conversions.id as conversion_id',
    //             DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //               SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as opening_balance'),
    //             DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as in_balance'),
    //             DB::raw('SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as out_balance'),
    //             DB::raw('SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) +
    //               SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //               SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) <= CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) as closing_balance'),
    //             DB::raw('((latest_prices.average_price / uom_conversions.conversion) *
    //               (SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) < CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) +
    //                SUM(CASE WHEN inventory_ledgers.action = "in" AND DATE(inventory_ledgers.date) = CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END) -
    //                SUM(CASE WHEN inventory_ledgers.action = "out" AND DATE(inventory_ledgers.date) <= CURDATE() THEN inventory_ledger_items.quantity ELSE 0 END))) as total_value')
    //         )
    //         ->where('items.id', $itemId)
    //         ->where('inventory_id', $inventoryId)
    //         ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
    //             $q->whereBetween(DB::raw('DATE(inventory_ledgers.created_at)'), [$fromDate, $toDate]);
    //         })
    //         ->when($fromDate && !$toDate, function ($q) use ($fromDate) {
    //             $q->whereDate('inventory_ledgers.created_at', '>=', $fromDate);
    //         })
    //         ->when(!$fromDate && $toDate, function ($q) use ($toDate) {
    //             $q->whereDate('inventory_ledgers.created_at', '<=', $toDate);
    //         })
    //         ->groupBy(
    //             'inventory_ledger_items.item_id',
    //             'items.name',
    //             'latest_prices.average_price',
    //             'latest_prices.base_uom_id',
    //             'items.base_uom_id',
    //             'uom_conversions.conversion',
    //             'item_uom.name',
    //             'base_uom.name'
    //         );
    // }

    public function MrpRawMaterials()
    {
        return $this->hasMany(MrpRawMaterial::class, 'item_id');
    }
}
