<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\JD\StoreJDrequest;
use App\Http\Requests\JD\JobSpecStoreRequest;
use App\Repositories\JobDescription\JobDescriptionRepositoryInterface;

class JobDescriptionController extends Controller
{
    protected JobDescriptionRepositoryInterface $jobDescriptionRepository;
    public function __construct(JobDescriptionRepositoryInterface $jobDescriptionRepository)
    {
    $this->jobDescriptionRepository = $jobDescriptionRepository;
    }

    public function getJobDescription(Request $request)
    {
        $jobDescription = $this->jobDescriptionRepository->getJobDescription($request);
        ResponseData($jobDescription);
    }
    public function storeJobDescription(StoreJDrequest $validatedData)
    {
        $jobDescription = $this->jobDescriptionRepository->storeJobDescription($validatedData->all());
        ResponseData($jobDescription);
    }
    public function showJobDescription(int $jobDescriptionId)
    {
        $jobDescription = $this->jobDescriptionRepository->showJobDescription($jobDescriptionId);
        ResponseData($jobDescription);
    }
    public function updateJobDescription(int $jobDescriptionId, StoreJDrequest $validatedData)
    {
        $jobDescription = $this->jobDescriptionRepository->updateJobDescription($jobDescriptionId, $validatedData->all());
        ResponseData($jobDescription);
    }
    public function deleteJobDescription(int $jobDescriptionId)
    {
        $jobDescription = $this->jobDescriptionRepository->deleteJobDescription($jobDescriptionId);
        ResponseData($jobDescription);
    }
    public function storeJobSpecification(JobSpecStoreRequest $validatedData)
    {
        $jobSpecification = $this->jobDescriptionRepository->storeJobSpecification($validatedData->all());
        ResponseData($jobSpecification);
    }
}