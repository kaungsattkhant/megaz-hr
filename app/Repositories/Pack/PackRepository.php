<?php

namespace App\Repositories\Pack;

use App\Models\Menu;
use App\Models\Pack;
use App\Models\PackItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackRepository implements PackRepositoryInterface
{
    public function createPack(array $data)
    {
        DB::beginTransaction();
        try {
            $data['created_by'] = UserData()->id;
            $menu = Menu::find($data['menu_id']);
            $menuItems = $menu->items;

            $pack = Pack::create([
                'menu_id' => $data['menu_id'],
                'date' => CurrentTime(),
                'expired_at' => $data['expired_at'],
                'created_by' => $data['created_by'],
                'status' => 'not yet'
            ]);

            foreach ($menuItems as $item) {
                $packItem = PackItem::create([
                    'pack_id' => $pack->id,
                    'item_id' => $item->id,
                    'uom_id' => $item->pivot->uom_id,
                    'quantity' => $item->pivot->weight
                ]);
            }

            DB::commit();
            return ResponseMessage("Packing successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseMessage($e->getMessage(), 402);
        }
    }

}
