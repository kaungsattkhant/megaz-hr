<?php

namespace App\Repositories\Package;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageRepository implements PackageRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $packages=  Package::paginate(config('common.list_count'));
        Responsedata($packages);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try{
            $data['created_by'] = UserData()->id;
            if(isset($data['roomSessionIds'])){
                $rooms = json_decode($data['roomSessionIds']);

            }
            dd('stop');
            $package = Package::create($data);
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
        }catch(\Exception $e)
        {
            DB::rollBack();
            ResponseMessage($e->getMessage());
            throw $e;
        }
    }

}
