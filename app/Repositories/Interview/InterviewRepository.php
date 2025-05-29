<?php

namespace App\Repositories\Interview;

use App\Models\Exam;

class InterviewRepository implements InterviewRepositoryInterface
{
  public function getInterviewsByRoleId($request, $roleId)
  {
    $exams =  Exam::where('role_id', $roleId)->where('type', 'interview')
      ->with(['examQuestions.answers', 'grades'])
      ->orderBy('created_at', 'desc')->paginate(config('common.list_count'));

    return $exams;
  }

  public function getInterviewById($interviewId)
  {
    $exam = Exam::with(['examQuestions.answers', 'grades'])
      ->where('id', $interviewId)
      ->where('type', 'interview')
      ->first();

    if (!$exam) {
      return ResponseMessage('Interview not found.', 404);
    }
    $groupedQuestions = $exam->examQuestions->groupBy('type');
    $exam->grouped_exam_questions = $groupedQuestions;
    unset($exam->examQuestions);
    return $exam;
  }

  public function createInterview(array $data)
  {
    // Logic to create a new interview
  }

  public function updateInterview(array $data, $id)
  {
    // Logic to update an existing interview
  }

  public function deleteInterview($id)
  {
    // Logic to delete an interview by ID
  }
}
