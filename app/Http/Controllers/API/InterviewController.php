<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interview\InterviewRepositoryInterface;

class InterviewController extends Controller
{
    private InterviewRepositoryInterface $interviewRepository;

    public function __construct(InterviewRepositoryInterface $interviewRepository)
    {
        $this->interviewRepository = $interviewRepository;
    }

    public function getInterviewsByRoleId(Request $request, $roleId)
    {
        $data = $this->interviewRepository->getInterviewsByRoleId($request, $roleId);
        ResponseData($data);
    }

    public function getInterviewById($interviewId)
    {
        $data = $this->interviewRepository->getInterviewById($interviewId);
        ResponseData($data);
    }

    public function storeInterview(Request $request)
    {
        $data = $this->interviewRepository->storeInterview($request->all());
        return response()->json($data);
    }

    public function getInterviewResults(Request $request)
    {
        $data = $this->interviewRepository->getInterviewResults($request);
        ResponseData($data);
    }
}
