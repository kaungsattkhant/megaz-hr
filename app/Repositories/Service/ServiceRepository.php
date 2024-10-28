<?php
namespace App\Repositories\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServiceRepository implements ServiceInterface
{
    public function list($request){
        return Service::with(['service_category','staff'])->paginate(20);
    }
    public function updateOrCreate($request){
        DB::beginTransaction();
        try {
            $data=$request->all();
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $service = Service::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            DB::commit();
            return $service;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($service){
        $service->load('staff');
        $service->load('service_category');
        return $service;
    }

}