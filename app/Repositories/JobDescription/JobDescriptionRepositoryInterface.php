<?php

namespace App\Repositories\JobDescription;

use Illuminate\Http\Request;

interface JobDescriptionRepositoryInterface
{
    public function getJobDescription(Request $request);

    public function storeJobDescription(array $validatedData);

    public function showJobDescription(int $jobDescriptionId);

    public function updateJobDescription(int $jobDescriptionId, array $validatedData);

    public function deleteJobDescription(int $jobDescriptionId);

    public function getJobSpecification(Request $request);

    public function storeJobSpecification(array $validatedData);

    public function showJobSpecification(int $jobSpecificationId);

    public function deleteJobSpecification(int $jobSpecificationId);

    public function storeSop(array $data);

    public function getSop(Request $request);

    public function showSop(int $jdId);

    public function deleteJdSopById(int $sopId);
}
