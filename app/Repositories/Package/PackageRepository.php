<?php

namespace App\Repositories\Package;

use App\Models\MenuPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageRepository implements PackageRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $packages=  Package::with('menuPackages.menu.prices','rooms')->paginate(config('common.list_count'));
        Responsedata($packages);
    }

    public function detailPackage(int $id)
    {
        $package = Package::where('id',$id)->with('menuPackages.menu.prices','rooms')->first();
        ResponseData($package);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try{
            $data['created_by'] = UserData()->id;
            $isValid = false;
            if(isset($data['roomIds'])){
                $roomIds = json_decode($data['roomIds']);
                $isValid = $this->validatePackingDates($roomIds, $data['from_date'], $data['to_date']);
            }
            if ($isValid) {
                ResponseMessage('Package dates overlap with existing packages for the specified rooms.', 422);
            }
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images/package_images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);

            $package = Package::create($data);
            if(isset($data['menuIds']))
            {
                $menuIds = json_decode($data['menuIds']);
                foreach($menuIds as $menu)
                {
                    $menu_package = MenuPackage::create([
                        'menu_id' => $menu->menu_id,
                        'quantity' => $menu->quantity,
                        'package_id' => $package->id
                    ]);
                }
            }
            if(isset($data['roomIds'])){
                $rooms = json_decode($data['roomIds']);
                foreach($rooms as $room)
                {
                    $package->rooms()->attach($room);
                }
            }
            DB::commit();
        ResponseData($package);
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 500);
            throw $e;
        }
    }


    public function validatePackingDates(array $roomIds, $fromDate, $toDate)
    {
        foreach ($roomIds as $roomId) {
            $overlappingPacking = Package::whereHas('rooms', function ($query) use ($roomId) {
                $query->where('entities.id', $roomId);
            })
                ->where(function ($query) use ($fromDate, $toDate) {
                    $query->where('from_date', '<=', $toDate)
                        ->where('to_date', '>=', $fromDate);
                })->first();

                // dd($overlappingPacking);
            if ($overlappingPacking==null) {
                return false;
            }
        }

        return true;
    }


    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try{
           if(isset($data['image']))
           {
            $imageData = $data['image'];
            $extension = $imageData->getClientOriginalExtension();
            $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
            $data['image_path'] = $imageData->storeAs('images', $hashedName, 'public');
            $data['image_url'] = Storage::url($data['image_path']);
           }
            $package = Package::find($id);
            $package->update($data);
            if(isset($data['roomIds']))
            {
                $roomIds = json_decode($data['roomIds']);
                $package->rooms()->sync($roomIds);
            }
            if (isset($data['menuIds'])) {
                $menuIds = json_decode($data['menuIds']);
                MenuPackage::where('package_id', $package->id)->delete();
                foreach ($menuIds as $menu) {
                    MenuPackage::create([
                        'menu_id' => $menu->menu_id,
                        'quantity' => $menu->quantity,
                        'package_id' => $package->id
                    ]);
                }
            }
            DB::commit();
            ResponseMessage($package);
        }catch(\Exception $e){
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

    public function deleteData(int $id)
    {
        DB::beginTransaction();
        try{
            $package = Package::find($id);
            $package->delete();
            DB::commit();
            ResponseMessage('Package deleted');
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

}
