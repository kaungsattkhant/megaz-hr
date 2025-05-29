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

    // public function createInterview(Request $request)
    // {
    //     $data = $this->interviewRepository->createInterview($request->all());
    //     return response()->json($data);
    // }

    // public function updateInterview(Request $request, $id)
    // {
    //     $data = $this->interviewRepository->updateInterview($request->all(), $id);
    //     return response()->json($data);
    // }

    // public function deleteInterview($id)
    // {
    //     $data = $this->interviewRepository->deleteInterview($id);
    //     return response()->json($data);
    // }
}
