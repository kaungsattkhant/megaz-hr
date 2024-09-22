<?php

namespace App\Repositories\Complaint;


use App\Models\Complaint;
use App\Models\ComplaintCarbonCopy;
use App\Models\ComplaintImage;
use App\Models\ComplaintResponsible;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Complaint::count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $complaints = Complaint::orderBy('id', 'desc')
                ->with('complaint_category')
                ->skip($skip)
                ->take($perPage)
                ->get();
            $complaints = MakePaginationData($request, $totalCount, 'complaints', $complaints);
            return $complaints;
        } else {
            $complaints = Complaint::orderBy('id', 'desc')->with('complaint_category')->get();
            return $complaints;
        }
    }

    public function complaintDetail(int $complainId)
    {
        $complaint = Complaint::where('id', $complainId)->with('complaint_category', 'postedBy', 'complaintResponsibles', 'complaintCarbonCopies', 'complaintImages')->first();
        ResponseData($complaint);
    }

    public function listComplaintsByStaff(Request $request, int $staffId)
    {
        if ($request->per_page || $request->page) {
            $totalCount = Complaint::where('posted_by', $staffId)->count();
            $pageNumber = 1;
            $perPage = 20;
            if ($request->page) {
                $pageNumber = $request->page;
            }
            if ($request->per_page) {
                $perPage = $request->per_page;
            }
            $skip = ($pageNumber - 1) * $perPage;
            $complaints = Complaint::where('posted_by', $staffId)->orderBy('id', 'desc')
                ->skip($skip)->take($perPage)->with('complaint_category')->get();
            $complaints = MakePaginationData($request, $totalCount, 'complaints', $complaints);

            return $complaints;
        } else {
            $complaints = Complaint::where('posted_by', $staffId)->with('complaint_category')->orderBy('id', 'desc')->get();

            return $complaints;
        }
    }

    public function complaintResponsiblesStaff()
    {
        $complaintResponsibles = ComplaintResponsible::where('staff_id', UserData()->id)->with('staff', 'complaint')->get();
        ResponseData($complaintResponsibles);
    }

    public function complaintCarbonCopiesStaff()
    {
        $carbonCopiesComplaints = ComplaintCarbonCopy::where('staff_id', UserData()->id)->with('staff', 'complaint')->get();
        ResponseData($carbonCopiesComplaints);
    }

    public function createData(array $data)
    {
        DB::beginTransaction();
        try {
            $data['posted_by'] = UserData()->id;
            $complaint = Complaint::create($data);
            if ($complaint) {
                $complaintResponsibles = json_decode($data['complaintResponsibles'], true);
                $complaintCarbonCopies = json_decode($data['complaintCarbonCopies'], true);
                if (!empty($complaintCarbonCopies)) {
                    foreach ($complaintResponsibles as $responsible) {
                        $complaint->complaintResponsibles()->create([
                            'staff_id' => $responsible,
                            'complaint_id' => $complaint->id
                        ]);
                    }
                }

                if (!empty($complaintCarbonCopies)) {
                    foreach ($complaintCarbonCopies as $carbonCopy) {
                        $complaint->complaintCarbonCopies()->create([
                            'staff_id' => $carbonCopy,
                            'complaint_id' => $complaint->id
                        ]);
                    }
                }

                if (!empty($data['complaintImages'])) {
                    if (is_string($data['complaintImages'])) {
                        $data['complaintImages'] = json_decode($data['complaintImages'], true);
                    }

                    foreach ($data['complaintImages'] as $image) {
                        $extension = $image->getClientOriginalExtension();
                        $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                        $data['image_path'] = $image->storeAs('images/complaint_images', $hashedName, 'public');
                        $data['image_url'] = Storage::url($data['image_path']);
                        $data['complaint_id'] = $complaint->id;
                        ComplaintImage::create($data);
                    }
                }
            }

            DB::commit();
            ResponseData($complaint);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function updateData(array $data, int $complaintId)
    {
        DB::beginTransaction();
        try {
            $complaint = Complaint::find($complaintId);
            if (!$complaint) {
                ResponseMessage("Complaint not found", 404);
                return;
            }

            $data['posted_by'] = UserData()->id;
            $complaint->update($data);

            $newResponsibles = json_decode($data['complaintResponsibles'], true);
            if (!empty($newResponsibles)) {
                $currentResponsibles = $complaint->complaintResponsibles()->pluck('staff_id')->toArray();

                $responsiblesToAdd = array_diff($newResponsibles, $currentResponsibles);
                foreach ($responsiblesToAdd as $responsible) {
                    $complaint->complaintResponsibles()->create([
                        'staff_id' => $responsible,
                        'complaint_id' => $complaint->id
                    ]);
                }

                $responsiblesToRemove = array_diff($currentResponsibles, $newResponsibles);
                $complaint->complaintResponsibles()->whereIn('staff_id', $responsiblesToRemove)->delete();
            }

            $newCarbonCopies = json_decode($data['complaintCarbonCopies'], true);
            if (!empty($newCarbonCopies)) {
                $currentCarbonCopies = $complaint->complaintCarbonCopies()->pluck('staff_id')->toArray();

                $carbonCopiesToAdd = array_diff($newCarbonCopies, $currentCarbonCopies);
                foreach ($carbonCopiesToAdd as $carbonCopy) {
                    $complaint->complaintCarbonCopies()->create([
                        'staff_id' => $carbonCopy,
                        'complaint_id' => $complaint->id
                    ]);
                }

                $carbonCopiesToRemove = array_diff($currentCarbonCopies, $newCarbonCopies);
                $complaint->complaintCarbonCopies()->whereIn('staff_id', $carbonCopiesToRemove)->delete();
            }

            if (!empty($data['complaintImages'])) {
                if (is_string($data['complaintImages'])) {
                    $data['complaintImages'] = json_decode($data['complaintImages'], true);
                }
                foreach ($data['complaintImages'] as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $hashedName = md5(uniqid() . microtime()) . '.' . $extension;
                    $imagePath = $image->storeAs('images/complaint_images', $hashedName, 'public');
                    $imageUrl = Storage::url($imagePath);

                    ComplaintImage::create([
                        'complaint_id' => $complaint->id,
                        'image_path' => $imagePath,
                        'image_url' => $imageUrl,
                    ]);
                }
            }

            DB::commit();
            ResponseData($complaint);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function deleteData($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            $complaint->delete();
            return true;
        } else {
            return false;
        }
    }

    public function statusChange(string $status, int $id)
    {
        $complaint = Complaint::find($id);

        if ($complaint !== null) {
            if ($complaint->status == $status) {
                ResponseMessage('Your status is already ' . "$status", 422);
            } else if ($complaint->status == "Done") {
                ResponseMessage("Status is already Done, cannot change", 422);
            }
            $complaint->status = $status;
            $complaint->save();
        }
        return $complaint;
    }


    public function deleteComplaintResponsible($id)
    {
        $complaintResponsible = ComplaintResponsible::find($id);
        $complaintResponsible->delete();
        ResponseMessage('Complaint responsible deleted', 200);
    }

    public function deleteComplaintCarbonCopy($id)
    {
        $complaintCarbonCopy = ComplaintCarbonCopy::find($id);
        $complaintCarbonCopy->delete();
        ResponseMessage('Complaint carbon copy deleted');
    }

    public function deleteComplaintImage($id)
    {
        $complaintImage = ComplaintImage::find($id);
        $complaintImage->delete();
        ResponseMessage('Complaint image deleted');
    }
}
