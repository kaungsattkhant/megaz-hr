<?php

namespace App\Repositories\Package;

use App\Models\MenuPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageRepository implements PackageRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $packages=  Package::with('menuPackages','rooms')->paginate(config('common.list_count'));
        Responsedata($packages);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try{
            $data['created_by'] = UserData()->id;
            $package = Package::create($data);
            $menu_package = MenuPackage::create([
                'menu_id' => $data['menu_id'],
                'quantity' => $data['quantity'],
                'package_id' => $package->id
            ]);

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
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

    public function editData(int $id, array $data)
    {
        DB::beginTransaction();
        try{
            $package = Package::find($id);

            $package->update($data);
            if(isset($data['roomIds']))
            {
                $roomIds = json_decode($data['roomIds']);
                $package->rooms()->sync($roomIds);
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
