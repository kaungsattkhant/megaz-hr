<?php

namespace App\Repositories\Staff;

use App\Models\Staff;

class StaffRepository implements StaffRepositoryInterface
{
    public function listAllData()
    {
        $staffs = Staff::all();
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

    public function updateData(array $data, $id)
    {
        if ($id) {
            $staff = Staff::find($id);
            $staff->update($data);
            if (isset($data['roles'])) {
                $staff->roles()->sync($data['roles']);
            }
        } else {
            $staff = Staff::create($data);
        }
        return $staff;
    }

    public function deleteData(Staff $staff)
    {
        $staff->is_active = 0;
        $staff->update();
        return true;
    }
}
