<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Exam\ExamRepositoryInterface;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private ExamRepositoryInterface $examRepository;
    public function __construct(ExamRepositoryInterface $examRepository)
    {
        $this->examRepository = $examRepository;
    }
    public function getAllExams(Request $request)
    {
        $data = $this->examRepository->getAllExams($request);
        ResponseData($data);
    }

    public function createExam(Request $request)
    {
        $data = $this->examRepository->createExam($request->all());
        ResponseData($data);
    }
}
