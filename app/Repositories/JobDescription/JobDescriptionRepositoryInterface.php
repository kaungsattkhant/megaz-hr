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

    public function storeJobSpecification(array $validatedData);
}
