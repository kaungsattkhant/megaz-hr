<?php

namespace App\Repositories\Menu;

use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\MenuPrice;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuRepository implements MenuRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();

        if ($request->per_page || $request->page) {
            $menu_category_id = $request->menu_category_id;

            return Menu::with(['menu_category', 'prices', 'items', 'menuServiceDiscounts' => function ($query) use ($validateDate) {
                $query->where('from_date', '<=', $validateDate)->where('to_date', '>=', $validateDate);
            }])
                ->when($request->search_input, function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search_input . '%');
                })
                ->when($menu_category_id, function ($query) use ($menu_category_id) {
                    $query->where('menu_category_id', $menu_category_id);
                })
                ->paginate(config('common.list_count'));
        } else {
            $menus = Menu::with(['menu_category', 'prices', 'items', 'menuServiceDiscounts' => function ($query) use ($validateDate) {
                $query->where('from_date', '<=', $validateDate)->where('to_date', '>=', $validateDate);
            }])->where('is_active', 1)->get();
            return $menus;
        }
    }

    public function createData(array $data, array $items, array $areas)
    {
        DB::beginTransaction();
        try {
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images/menu_images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);
            $menu = Menu::create($data);
            $this->createMenuPrice($menu->id, $data['price']);
            foreach ($items as $item) {
                $menu->items()->attach($item['id'], [
                    'uom_id' => $item['uom_id'],
                    'weight' => $item['weight'],
                    'price' => $item['price'],
                    'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                ]);
            }

            foreach ($areas as $area) {
                $menu->areas()->attach($area);
            }
            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function createMenuPrice(int $id, float $price)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $menuPrice = MenuPrice::create([
                "menu_id" => $menu->id,
                "price" => $price
            ]);

            return $menuPrice;
        }

        return null;
    }

    public function menuDetail(int $id)
    {
        // $menu = Menu::find($id)->with('items.uoms', 'prices', 'menu_category')->first();
        $menu = Menu::with('items.uoms', 'prices', 'menu_category', 'areas')->find($id);
        return $menu;
    }

    public function menuIsActive(int $id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::find($id);
            $menu->is_active = !$menu->is_active;
            $menu->save();
            DB::commit();
            ResponseMessage('Menu is active status has been changed');
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function editMenu(int $id, array $data, array $items, array $areas)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::findOrFail($id);

            if (isset($data['image'])) {
                $imageData = $data['image'];
                $extension = $imageData->getClientOriginalExtension();
                $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                $data['image_path'] = $imageData->storeAs('images', $hashedName, 'public');
                $data['image_url'] = Storage::url($data['image_path']);
            }

            $menu->update($data);

            if (isset($areas)) {
                $menu->areas()->detach();
                foreach ($areas as $area) {
                    $menu->areas()->attach($area);
                }
            }

            if (isset($data['price'])) {
                $this->updateMenuPrice($menu->id, $data['price']);
            }
            if (isset($items)) {
                // $menu->items()->detach();
                $syncData = [];
                foreach ($items as $item) {
                    if ($menu->items()->where('item_id', $item['id'])->where('uom_id', $item['uom_id'])->first()) {
                        $menu->items()->detach($item['id']);
                    }
                    $syncData[$item['id']] = [
                        'uom_id' => $item['uom_id'],
                        'weight' => $item['weight'],
                        'price' => $item['price'],
                        'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                    ];

                    $menu->items()->attach($item['id'], [
                        'uom_id' => $item['uom_id'],
                        'weight' => $item['weight'],
                        'price' => $item['price'],
                        'is_make_pack' => $item['is_make_pack'] ? 1 : 0
                    ]);
                }
                // $menu->items()->sync($syncData);
            }

            DB::commit();
            return $menu;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function updateMenuPrice(int $id, float $price)
    {
        $menuPrice = MenuPrice::where('menu_id', $id)->latest()->first();
        if ($menuPrice) {
            if ($menuPrice->price != $price) {
                MenuPrice::create([
                    'menu_id' => $id,
                    'price' => $price
                ]);
            }
        }
    }

    public function toggleMenuFeature($id)
    {
        DB::beginTransaction();
        try {
            $menu = Menu::find($id);
            if ($menu) {
                $menu->is_feature = !$menu->is_feature;
                $menu->update();
                DB::commit();
                ResponseMessage('Menu is feature status has been changed');
            } else {
                ResponseMessage('Menu not found', 404);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function menuAreaList($id)
    {
        $menus = Menu::with('areas')->find($id);
        ResponseData($menus);
    }


    public function menuReport(Request $request)
    {
        // Get from_month and to_month as YYYY-MM
        $fromMonthInput = $request->input('from_month', Carbon::now()->format('Y-m'));
        $toMonthInput = $request->input('to_month', Carbon::now()->format('Y-m'));

        // Extract year and month from fromMonth and toMonth
        [$fromYear, $fromMonth] = explode('-', $fromMonthInput);
        [$toYear, $toMonth] = explode('-', $toMonthInput);

        // Capture the search term and filter for menu name and category ID
        $searchTerm = $request->input('search', null); // Search for menu_name
        $menuCategoryId = $request->input('menu_category_id', null); // Filter by menu_category_id

        // Build the query
        $orderSummaryQuery = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('menu_categories', 'menus.menu_category_id', '=', 'menu_categories.id')
            ->select(
                'order_items.menu_id',
                'menus.code as menu_code', // Include menu code in the select
                'menus.name as menu_name',
                'menu_categories.name as menu_category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('MONTH(order_items.created_at) as month'),
                DB::raw('YEAR(order_items.created_at) as year')
            );


        // Apply search filter if searchTerm exists (on menu_name)
        if (!empty($searchTerm)) {
            $orderSummaryQuery->where('menus.name', 'LIKE', "%{$searchTerm}%");
        }

        // Apply category filter if menu_category_id exists
        if (!empty($menuCategoryId)) {
            $orderSummaryQuery->where('menus.menu_category_id', '=', $menuCategoryId);
        }

        // Apply month and year filters
        $orderSummaryQuery->whereBetween('order_items.created_at', [
            Carbon::createFromFormat('Y-m', $fromYear . '-' . $fromMonth)->startOfMonth(),
            Carbon::createFromFormat('Y-m', $toYear . '-' . $toMonth)->endOfMonth()
        ]);

        // Group the results by menu_id, menu_code, menu_name, menu_category, month, and year
        $orderSummary = $orderSummaryQuery
            ->groupBy('order_items.menu_id', 'menus.code', 'menus.name', 'menu_categories.name', 'month', 'year')
            ->get();

        ResponseData($orderSummary);

        // Post-process the query result to structure the data by menu_id
        $summaryData = collect($orderSummary)->groupBy('menu_id')->map(function ($items, $key) use ($fromMonthInput, $toMonthInput) {
            $firstItem = $items->first();

            // Start with basic details
            $summary = [
                'menu_id' => $firstItem->menu_id,
                'menu_code' => $firstItem->menu_code, // Include menu code in the summary
                'menu_name' => $firstItem->menu_name,
                'menu_category' => $firstItem->menu_category,
                'total_quantity' => $items->sum('total_quantity'), // Sum across all months
            ];

            // Generate the month range
            $start = Carbon::createFromFormat('Y-m', $fromMonthInput);
            $end = Carbon::createFromFormat('Y-m', $toMonthInput);

            // Loop through the months and add monthly quantities
            while ($start->lte($end)) {
                $monthName = $start->format('F');
                $monthlyQuantity = $items->where('month', $start->month)->sum('total_quantity');

                // Add the monthly quantity to the summary
                $summary[$monthName] = $monthlyQuantity;

                // Move to the next month
                $start->addMonth();
            }

            return $summary;
        })->values();

        // Return the response data
        ResponseData($summaryData);
    }


    public function costingMenu(Request $request)
    {
        // Capture the date parameter for filtering
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $menuCategoryId = $request->input('menu_category_id', null);

        // Build the query
        $orderSummaryQuery = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('menu_categories', 'menus.menu_category_id', '=', 'menu_categories.id')
            ->join('menu_prices', 'menus.id', '=', 'menu_prices.menu_id') // Join to get menu prices
            ->join('item_menu', 'menus.id', '=', 'item_menu.menu_id') // Join to get item prices
            ->join('items', 'item_menu.item_id', '=', 'items.id') // Join to get items
            ->select(
                'order_items.menu_id',
                'menus.code as menu_code', // Include menu code in the select
                'menus.name as menu_name',
                'menu_categories.name as menu_category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * menu_prices.price) as total_price'), // Total price calculation for menu
                DB::raw('SUM(item_menu.price * order_items.quantity) as items_total_price') // Total price calculation for items
            );

        // Apply category filter if menu_category_id exists
        if (!empty($menuCategoryId)) {
            $orderSummaryQuery->where('menus.menu_category_id', '=', $menuCategoryId);
        }

        // Apply date filter if provided
        if ($date) {
            $orderSummaryQuery->whereDate('order_items.created_at', '=', $date); // Filter by specific date
        }

        // Group the results by menu_id, menu_name, and menu_category
        $orderSummary = $orderSummaryQuery
            ->groupBy('order_items.menu_id', 'menus.code', 'menus.name', 'menu_categories.name') // Group by menu code
            ->get();

        // Check if any results are returned
        if ($orderSummary->isEmpty()) {
            Response('No data found', 404);
        }

        // Format the response
        $summaryData = collect($orderSummary)->map(function ($item) {

            return [
                'menu_id' => $item->menu_id,
                'menu_code' => $item->menu_code, // Add menu code to response
                'menu_name' => $item->menu_name,
                'menu_category' => $item->menu_category,
                'total_quantity' => $item->total_quantity,
                'total_sale_price' => $item->total_price, // Total price of the menu based on price and quantity
                'total_cost_price' => $item->items_total_price, // Total price of items associated with the menu
                'total_gp' => $item->total_price - $item->items_total_price, // Gross Profit
                'total_gp_percentage' => ($item->total_price - $item->items_total_price) / $item->total_price * 100, // Gross Profit Percentage

            ];
        });

        ResponseData($summaryData);
    }

    // user app

    public function listAllMenu(Request $request)
    {
        $validateDate = $request->date ?? CurrentDate();
        $menus = Menu::with([
            'menu_category',
            'prices',
            'items',
            'menuServiceDiscounts' => function ($query) use ($validateDate) {
                $query->where('from_date', '<=', $validateDate)->where('to_date', '>=', $validateDate);
            }
        ])->where('is_feature', 1)->orderBy('created_at', 'desc')->where('is_active', 1)->paginate(config('common.list_count'));
        return $menus;
    }
}
