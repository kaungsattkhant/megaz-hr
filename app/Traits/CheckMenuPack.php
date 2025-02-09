<?php
namespace App\Traits;

use App\Models\Pack;
use Illuminate\Support\Facades\DB;


trait CheckMenuPack
{

    public function removePackForMenu($menuId,$orderQauntity)
    {
        $packQauntityByMenu=$this->getPackQauntityByMenu($menuId);
        if($packQauntityByMenu<$orderQauntity){
            return ResponseMessage('Pack is not enought for Menu',419);
        }
        $this->removePacks($menuId,$orderQauntity);
        return true;
    }

    public function removePacks($menuId,$orderQauntity){
        $packToRemove=Pack::where('menu_id',$menuId)
        ->take($orderQauntity)
        ->orderBy('expired_at','asc')
        ->get();
        $packToRemove->each->delete();
        // $packToRemove->delete();
    }

    public function getPackQauntityByMenu($menuId)
    {
        $packByMenu = Pack::join('menus', 'packs.menu_id', 'menus.id')
            ->select(
                'menus.id as menu_id',
                'menus.name as menu_name',
                DB::raw('COUNT(packs.id) as total_pack_quantity') // Count the total number of packs
                // DB::select('SUM(packs.quantity) as total_quantity'),
            )
            ->where('status','ready')
            ->where('menus.id',$menuId)
            ->groupBy('menus.id')
            ->first();
        return  $packByMenu ->total_pack_quantity ?? 0;
    }
}