<?php

namespace App\Services;

use App\Models\Entity;



class InvoiceService
{
    public function updateEntityStatus($entityId,$status){
        return Entity::where('id',$entityId)->update([
            'is_active'=>$status=='inactive' ? 0 :1,
            'status'=>$status,
        ]);
    }
}