<?php
namespace App\Repositories\Canteen;

use App\Models\Canteen;
use App\Models\UomConversion;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Inventory\StoreInventory;
use App\Http\Action\Inventory\InventoryLedger;
use Illuminate\Pagination\LengthAwarePaginator;

class CanteenRepository implements CanteenInterface
{
    public function list($request)
    {

        //     $canteenItemsUsage = DB::table('canteens')
        // ->join('canteen_items', 'canteens.id', '=', 'canteen_items.canteen_id')
        // ->join('items', 'canteen_items.item_id', '=', 'items.id')
        // ->join('uoms', 'canteen_items.uom_id', '=', 'uoms.id')
        // ->select(
        //     DB::raw("DATE_FORMAT(canteens.date, '%Y-%m-%d') as date"),
        //     'items.name as item',
        //     'canteen_items.quantity',
        //     'uoms.name as uom_name',
        //     'canteen_items.amount'
        // )
        // ->orderBy('canteens.date') // Order by the date
        // ->get()
        // ->groupBy('date') // Group the results by date
        // ->map(function ($items, $date) {
        //     return [
        //         'date' => $date,
        //         'canteen_items' => $items->toArray()
        //     ];
        // })
        // ->values();
        //correct
        $canteenItemsUsage = DB::table('canteens')
            ->join('canteen_items', 'canteens.id', '=', 'canteen_items.canteen_id')
            ->join('items', 'canteen_items.item_id', '=', 'items.id')
            ->join('uoms', 'canteen_items.uom_id', '=', 'uoms.id')
            ->select(
                DB::raw("DATE_FORMAT(canteens.date, '%Y-%m-%d') as date"),
                'items.name as item',
                'canteen_items.quantity',
                'uoms.name as uom_name',
                'canteen_items.amount'
            )
            ->orderBy('canteens.date') // Order by the date
            ->get()
            ->groupBy('date') // Group the results by date
            ->map(function ($items, $date) {
                // Calculate total amount for each date
                $totalAmount = $items->sum(function ($item) {
                    return $item->amount * $item->quantity; // sum up the amount of each item
                });
                // Prepare the final structure
                return [
                    'date' => $date,
                    'total_amount' => $totalAmount, // Add total amount here
                    'canteen_items' => $items->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'item' => $item->item,
                        'quantity' => $item->quantity,
                        'uom_name' => $item->uom_name,
                        'amount' => $item->amount,
                        'sub_amount' => $item->quantity * $item->amount // Calculate sub amount
                    ];
                })->values() // Ensure we return an array
                ];
            })
            ->values();
        //end correct
        $perPage = 20;
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified
        $currentPage = $request->input('page', 1); // Get the current page or default to 1

        // Perform the initial query without pagination
        $canteenItemsUsage = DB::table('canteens')
            ->join('canteen_items', 'canteens.id', '=', 'canteen_items.canteen_id')
            ->join('items', 'canteen_items.item_id', '=', 'items.id')
            ->join('uoms', 'canteen_items.uom_id', '=', 'uoms.id')
            ->select(
                DB::raw("DATE_FORMAT(canteens.date, '%Y-%m-%d') as date"),
                'items.name as item',
                'canteen_items.quantity',
                'uoms.name as uom_name',
                'canteen_items.amount'
            )
            ->orderBy('canteens.date')
            ->get();

        // Group and transform the data
        $groupedData = $canteenItemsUsage->groupBy('date')->map(function ($items, $date) {
            // Calculate total amount for each date
            $totalAmount = $items->sum(function ($item) {
                return $item->amount * $item->quantity;
            });
            // Prepare the final structure
            return [
                'date' => $date,
                'total_amount' => $totalAmount,
                'canteen_items' => $items->map(function ($item) {
                    return [
                        'date' => $item->date,
                        'item' => $item->item,
                        'quantity' => $item->quantity,
                        'uom_name' => $item->uom_name,
                        'amount' => $item->amount,
                        'sub_amount' => $item->quantity * $item->amount,
                    ];
                })->values()
            ];
        })->values();

        // Manually create a paginator
        $total = $groupedData->count();
        $paginatedData = new LengthAwarePaginator(
            $groupedData->forPage($currentPage, $perPage), // Items for the current page
            $total, // Total items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => $request->url(), 'query' => $request->query()] // Pagination options
        );

        // Return the paginated results
        return $paginatedData;
        // return $canteenItemsUsage;
    }

    public function store($request)
    {
        $data = $request->all();
        $jsonDecoded = json_decode($request->items);
        // $inventoryId = UserData()->inventories[0]->id;
        // $inventoryId=UserData()->department->inventory->inventory_id;
        $inventoryId = 14;

        // dd($inventoryId);
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $data['created_by'] = 1;
            $canteen = Canteen::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $inventoryLedger = (new StoreInventory($inventoryId))->storeToInventoryLedger($canteen, 'canteen', 'out');
            foreach ($jsonDecoded as $item) {
                $conversionUom=UomConversion::find($item->uom_conversion_id);
                // dd($conversionUom);
                $quantity=$conversionUom->conversion*$item->quantity;
                (new InventoryLedger($inventoryId))->isEnoughQuantityByItem($item->item_id, $quantity);
                if (isset($item->id) && $item->id !== null) {
                    $canteen_item['id'] = $item->id;
                } else {
                    $canteen_item['id'] = null;
                }
                $canteen_item['canteen_id'] = $canteen->id;
                $canteen_item['uom_id'] = $item->uom_id;
                $canteen_item['item_id'] = $item->item_id;
                $canteen_item['uom_conversion_id'] = $item->uom_conversion_id;
                $canteen_item['quantity'] = $item->quantity;
                $canteen_item['amount'] = $item->amount;
                $canteenItem = $canteen->canteen_items()->updateOrCreate(['id' => $canteen_item['id']], $canteen_item);

                // $inventoryId = $inventoryId;
                (new StoreInventory($inventoryId))->storeItemToInventory($inventoryLedger, $canteenItem);
            }
            DB::commit();
            return $canteen;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
}