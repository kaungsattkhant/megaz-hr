<?php

namespace App\Repositories\Complain;

use App\Models\Complain;

class ComplainRepository implements ComplainRepositoryInterface
{
    public function listAllData()
    {
        $complains = Complain::all();
        return $complains;
    }

    public function createData(array $data)
    {
        $data['status'] = 'Not yet';
        $complain = Complain::create($data);
        return $complain;
    }

    public function updateData(array $data,$id)
    {
        if(isset($id))
        {
            $complain = Complain::find($id);
            $complain->update($data);
        }else{
            $data['status'] = 'Not yet';
            $complain = Complain::creat($data);
        }
        return $complain;
    }

    public function deleteData($id)
    {
        $complain = Complain::find($id);
        if($complain)
        {
            $complain->delete();
            return true;
        }else{
            return false;
        }
    }
}
