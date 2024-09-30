<?php

namespace App\Repositories\Complaint;

use Illuminate\Http\Request;

interface ComplaintRepositoryInterface
{
    public function listAllData(Request $request);

    public function complaintDetail(int $complainId);

    public function listComplaintsByStaff(Request $request, int $staffId);

    public function createData(array $data);

    public function updateData(array $data,int $id);

    public function statusChange(string $data,int $id);

    public function deleteData($id);

    public function complaintResponsiblesStaff();

    public function complaintCarbonCopiesStaff();

    public function deleteComplaintResponsible($id);

    public function deleteComplaintCarbonCopy($id);

    public function deleteComplaintImage($id);
}
