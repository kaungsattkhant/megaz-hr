<?php

namespace App\Repositories\Staff;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffRepository implements StaffRepositoryInterface
{
    public function listAllData(Request $request)
    {
        $allStaffs = Staff::with('department')->get();
        $staffs = Pagination($allStaffs, $request, 'staffs');
        return $staffs;
    }

    public function createData(array $data)
    {
        $data['is_active'] = 1;
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
