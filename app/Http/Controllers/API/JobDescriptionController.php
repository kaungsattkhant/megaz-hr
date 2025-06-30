<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\JD\StoreJDrequest;
use App\Http\Requests\JD\SopStoreRequest;
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

    public function getJobSpecification(Request $request)
    {
        $jobSpecification = $this->jobDescriptionRepository->getJobSpecification($request);
        ResponseData($jobSpecification);
    }
    public function showJobSpecification(int $jobSpecificationId)
    {
        $jobSpecification = $this->jobDescriptionRepository->showJobSpecification($jobSpecificationId);
        ResponseData($jobSpecification);
    }
    public function deleteJobSpecification(int $jobSpecificationId)
    {
        $jobSpecification = $this->jobDescriptionRepository->deleteJobSpecification($jobSpecificationId);
        ResponseData($jobSpecification);
    }
    public function storeSop(SopStoreRequest $validatedData)
    {
        $sop = $this->jobDescriptionRepository->storeSop($validatedData->all());
        ResponseData($sop);
    }

    public function getSop(Request $request)
    {
        $sop = $this->jobDescriptionRepository->getSop($request);
        ResponseData($sop);
    }
    public function showSop(int $sopId)
    {
        $sop = $this->jobDescriptionRepository->showSop($sopId);
        ResponseData($sop);
    }
    public function deleteSop(int $sopId)
    {
        $sop = $this->jobDescriptionRepository->deleteSop($sopId);
        ResponseData($sop);
    }
}