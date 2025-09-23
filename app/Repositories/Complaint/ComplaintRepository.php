<?php

namespace App\Repositories\Complaint;

use App\Events\ComplaintNotificationRequest;
use App\Http\Action\SendNotification\SendNotification;
use App\Models\Complaint;
use App\Models\ComplaintCarbonCopy;
use App\Models\ComplaintImage;
use App\Models\ComplaintResponsible;
use App\Models\Staff;
use App\Repositories\Complaint\ComplaintRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    use SendNotification;

    public function listAllData(Request $request)
    {
        if ($request->per_page || $request->page) {
            $complaints = Complaint::orderBy('id', 'desc')
                ->with('complaint_category')
                ->paginate(config('common.list_count'));
            return $complaints;
        } else {
            $complaints = Complaint::orderBy('id', 'desc')->with('complaint_category')->get();
            return $complaints;
        }
    }

    public function complaintDetail(int $complainId)
    {
        $complaint = Complaint::where('id', $complainId)->with('complaint_category', 'postedBy', 'complaintResponsibles.staff', 'complaintCarbonCopies.staff', 'complaintImages')->first();
        ResponseData($complaint);
    }

    public function listComplaintsByStaff(Request $request, int $staffId)
    {
        if ($request->per_page || $request->page) {
            $complaints = Complaint::where('posted_by', $staffId)->orderBy('id', 'desc')->with('complaint_category')->paginate(config('common.list_count'));
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
                        // $msg = 'You are responsible. Please Check';
                        // broadcast(new ComplaintNotificationRequest($complaint, $msg, $responsible));
                        $this->complaintNotification($complaint, $responsible);
                    }
                    $staffs = Staff::whereIn('id', $complaintResponsibles)->get();
                    $notificationsArray = $staffs->map(function($staff) {
                        return [
                            'id' => $staff->id,
                            'title' => UserData()->name . ' added a new complaint', // Assuming UserData()->name gives the current user's name
                            'preview' => 'You are responsible, Please check',
                            'type' => 'complaint_responsible'
                        ];
                    })->toArray();

                    $data = [
                        'date' => $complaint->created_at,
                        'title' => 'Complaints',
                        'body' => 'You need To Check',
                    ];

                    if($staffs->isNotEmpty()){
                        $this->sendNoti($complaint, $notificationsArray, $data);
                    }
                }

                if (!empty($complaintCarbonCopies)) {
                    foreach ($complaintCarbonCopies as $carbonCopy) {
                        $complaint->complaintCarbonCopies()->create([
                            'staff_id' => $carbonCopy,
                            'complaint_id' => $complaint->id
                        ]);

                    // $msg = 'You need to check.';
                    // broadcast(new ComplaintNotificationRequest($complaint, $msg, $carbonCopy));
                    $this->complaintNotification($complaint, $carbonCopy);
                    }
                    
                    $staffs = Staff::whereIn('id', $complaintCarbonCopies)->get();
                    $notificationsCarbonCopy = $staffs->map(function($staff) {
                        return [
                            'id' => $staff->id,
                            'title' => UserData()->name . ' added a new complaint', // Assuming UserData()->name gives the current user's name
                            'preview' => 'You need to check',
                            'type' => 'complaint_cc'
                        ];
                    })->toArray();

                    $data = [
                        'date' => $complaint->created_at,
                        'title' => 'Complaints',
                        'body' => 'You need To Check',
                    ];
                    if($staffs->isNotEmpty()){
                        $this->sendNoti($complaint, $notificationsCarbonCopy, $data);
                    }
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
                            'image_url' => $imageUrl
                        ]);
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

            $oldComplaint = $complaint->status;


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

            $data['posted_by'] = UserData()->id;
            if($oldComplaint != $data['status'])
            {
                // $msg = `You need to check`;
                // broadcast(new ComplaintNotificationRequest($complaint, $msg, UserData()->id));
                $this->complaintNotification($complaint, UserData()->id);
                $staffs = Staff::where('id', UserData()->id)->get();
                $notificationUser = $staffs->map(function($staff) use ($data,$complaint) {
                    return [
                        'id' => $complaint->postedBy->id,
                        'title' => UserData()->name . ' ' . $data['status'].' ' . $complaint->postedBy->name. "'s complaints",
                        'preview' => 'You need to check',
                        'type' => 'my_complaint'
                    ];
                })->toArray();

                $data = [
                    'date' => $complaint->created_at,
                    'title' => 'Complaints',
                    'body' => 'You need To Check',
                ];

                if($staffs->isNotEmpty()){
                    $this->sendNoti($complaint, $notificationUser, $data);
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

