<?php

namespace App\Repositories\JobDescription;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\JobDescription;
use App\Models\JobSpecification;
use Illuminate\Support\Facades\DB;


class JobDescriptionRepository implements JobDescriptionRepositoryInterface
{
    public function getJobDescription(Request $request)
    {
        return JobDescription::orderBy('id', 'desc')->paginate(config('common.list_count'));
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
}