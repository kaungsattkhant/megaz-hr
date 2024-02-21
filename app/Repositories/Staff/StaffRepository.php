<?php

namespace App\Repositories\Staff;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffRepository implements StaffRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if($request->per_page || $request->page){
            $totalCount = Staff::where('is_active', 1)->count();
            $pageNumber = 1;
            $perPage = 20;
            if($request->page){
                $pageNumber = $request->page;
            }
            if($request->per_page){
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $staffs = Staff::where('is_active', 1)->skip($skip)->take($perPage)->with('department')->get();
            $paginationData = MakePaginationData($request, $totalCount, 'staffs');
            $paginationData['staffs'] = $staffs;

            return $paginationData;
        }
        else{
            $staffs = Staff::where('is_active', 1)->get();

            return $staffs;
        }
    }

    public function createData(array $data)
    {
        $data['is_active'] = 1;
        $data = RemoveNullValues($data);
        $staff = Staff::create($data);

        if (isset($data['roles']) && is_array($data['roles'])) {
            $staff->roles()->attach($data['roles']);
        }
        return $staff;
    }

    public function updateData(array $data,int $id)
    {

        $staff = Staff::find($id);
        if ($staff) {

            $data = RemoveNullValues($data);

            $staff->update($data);
            if (isset($data['roles'])) {
                $staff->roles()->sync($data['roles']);
            }
        }

        return $staff;
    }

    public function deleteData($id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->is_active = 0;
            $staff->save();

            return true;
        }

        return false;
    }
}
