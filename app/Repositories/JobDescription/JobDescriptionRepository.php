<?php

namespace App\Repositories\JobDescription;

use App\Models\Sop;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\JobDescription;
use App\Models\JobSpecification;
use Illuminate\Support\Facades\DB;


class JobDescriptionRepository implements JobDescriptionRepositoryInterface
{
    public function getJobDescription(Request $request)
    {
        $roleId = $request->role_id;
        return JobDescription::with('role.department')
        ->when($roleId, function ($query) use ($roleId){
            return $query->where('role_id', $roleId);
        })
        ->orderBy('id', 'desc')
        ->paginate(config('common.list_count'));
    }
    public function storeJobDescription(array $validatedData)
    {
        return JobDescription::firstOrCreate($validatedData);
    }
    public function showJobDescription(int $jobDescriptionId)
    {
        return JobDescription::findOrFail($jobDescriptionId);
    }
    public function updateJobDescription(int $jobDescriptionId, array $validatedData)
    {
        $jobDescription = JobDescription::findOrFail($jobDescriptionId);
        $jobDescription->update($validatedData);
        return $jobDescription;
    }
    public function deleteJobDescription(int $jobDescriptionId)
    {
        $jobDescription = JobDescription::findOrFail($jobDescriptionId);
        $jobDescription->delete();
        return $jobDescription;
    }
    public function storeJobSpecification(array $validatedData)
    {
        DB::beginTransaction();
        try {
            $validatedData['created_by'] = UserData()->id;
            $jobSpecData = Arr::except($validatedData, ['skills']);

            $js = JobSpecification::updateOrCreate(
                ['id' => $validatedData['id'] ?? null], $jobSpecData);
            if(isset($validatedData['skills'])) {
                $js->skills()->sync($validatedData['skills']);
            }
            DB::commit();
            return $js->load('skills'); 
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function getJobSpecification(Request $request)
    {
        $roleId = $request->role_id;
        return JobSpecification::with(['jobDescription.role.department','createdBy'])
        ->when($roleId, function ($query) use ($roleId) {
            return $query->whereHas('jobDescription', function ($q) use ($roleId) {
                $q->where('role_id', $roleId);
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(config('common.list_count'));
    }

    public function showJobSpecification(int $jobSpecificationId)
    {
        return JobSpecification::with(['jobDescription.role.department','createdBy','skills.role.department'])->findOrFail($jobSpecificationId);
    }
    public function deleteJobSpecification(int $jobSpecificationId)
    {
        $jobSpecification = JobSpecification::findOrFail($jobSpecificationId);
        $jobSpecification->delete();
        return $jobSpecification;
    }

    public function getSop(Request $request)
    {
        $roleId = $request->role_id;
        return Sop::with(['jobDescription.role.department','role.department'])
        ->when($roleId, function ($query) use ($roleId) {
            return $query->where('role_id', $roleId);
        })
        ->orderBy('id', 'desc')
        ->paginate(config('common.list_count'));
    }

    public function showSop(int $sopId)
    {
        return Sop::with(['jobDescription.role.department','role.department'])->findOrFail($sopId);
    }

    public function storeSop(array $data)
    {
        DB::beginTransaction();
        try {
            $sop = Sop::updateOrCreate(
                ['id' => $data['id'] ?? null], $data);
            DB::commit();
            return $sop;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function deleteSop(int $sopId)
    {
        $sop = Sop::findOrFail($sopId);
        $sop->delete();
        return $sop;
    }
}
