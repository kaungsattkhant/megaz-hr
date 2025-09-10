<?php

namespace App\Repositories\JobDescription;

use App\Models\Sop;
use App\Models\JdSop;
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
        $query = JobDescription::with('role.department')
            ->when($roleId, function ($query) use ($roleId) {
                return $query->where('role_id', $roleId);
            })
            ->orderBy('id', 'desc');
        if ($request->per_page || $request->page) {
            return $query->paginate(config('common.list_count'));
        } else {
            return $query->get();
        }
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
        // $jobSpecifications = jobSpecification::where('job_description_id', $jobDescriptionId)->get();
        // $jdSops = JdSop::where('job_description_id', $jobDescriptionId)->get();
        // if ($jobSpecifications->isNotEmpty() || $jdSops->isNotEmpty()) {
        //     foreach ($jobSpecifications as $jobSpecification) {
        //         $jobSpecification->delete();
        //     }
        //     foreach ($jdSops as $jdSop) {
        //         $jdSop->sops()->delete();
        //         $jdSop->delete();
        //     }
        // }
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
                ['id' => $validatedData['id'] ?? null],
                $jobSpecData
            );
            if (isset($validatedData['skills'])) {
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
        return JobSpecification::with(['jobDescription.role.department', 'createdBy', 'skills.role.department'])
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
        return JobSpecification::with(['jobDescription.role.department', 'createdBy', 'skills.role.department'])->findOrFail($jobSpecificationId);
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
        $query =  JdSop::with(['jobDescription' => function ($query){
            $query->withTrashed();
        },'jobDescription.role.department', 'sops.role.department'])
            ->when($roleId, function ($query) use ($roleId) {
                return $query->whereHas('sops', function ($q) use ($roleId) {
                    $q->where('role_id', $roleId);
                });
            })
            ->orderBy('id', 'desc');
        if ($request->per_page || $request->page) {
            return $query->paginate(config('common.list_count'));
        } else {
            return $query->get();
        }
    }

    public function showSop(int $jdId)
    {
        return JdSop::with(['sops.role.department', 'jobDescription.role.department'])->findOrFail($jdId);
    }

    public function storeSop(array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['sops'])) {
                $decodedSops = json_decode($data['sops'], true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return ResponseMessage('Invalid JSON data provided for sop items.', 400);
                }
            }
            $jdSop = JdSop::updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'job_description_id' => $data['job_description_id']
                ]
            );

            foreach ($decodedSops as $sopData) {
                $sop = Sop::updateOrCreate(
                    ['id' => $sopData['id'] ?? null],
                    [
                        'jd_sop_id' => $jdSop->id,
                        'sop' => $sopData['sop'],
                        'role_id' => $sopData['role_id'],
                    ]
                );
            }
            DB::commit();
            return $sop;
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }
    public function deleteJdSopById(int $jdSopId)
    {

        $jdSop = JdSop::findOrFail($jdSopId);
        // $jdSop->sops()->each(function ($sop) {
        //     $sop->delete();
        // });
        $jdSop->delete();
        return $jdSop;
    }
}
