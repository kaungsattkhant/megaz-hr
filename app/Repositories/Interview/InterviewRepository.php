<?php

namespace App\Repositories\Interview;

use App\Models\Exam;
use App\Models\Interview;
use App\Models\CustomQuestion;
use App\Models\InterviewAnswer;
use Illuminate\Support\Facades\DB;

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
    $groupedQuestions = $exam->examQuestions->groupBy('type')->toArray();
    $exam['grouped_exam_questions'] = array_values($groupedQuestions);
    unset($exam->examQuestions);
    return $exam;
  }

  public function storeInterview(array $data)
  {
    DB::beginTransaction();
    try {
      $data['created_by'] = UserData()->id;
      $interview = Interview::create($data);
      if (isset($data['interview_answers'])) {
        $interviewAnswers = json_decode($data['interview_answers'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for  interview answers.', 400);
        }
        foreach ($interviewAnswers as $interviewAnswer) {
          InterviewAnswer::create([
            'interview_id' => $interview->id,
            'exam_question_id' => $interviewAnswer['exam_question_id'],
            'answer_id' => $interviewAnswer['answer_id'],
          ]);
        }
      }
      if (isset($data['custom_questions'])) {
        $customQuestions  = json_decode($data['custom_questions'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for custom questions.', 400);
        }
        foreach ($customQuestions as $question) {
          CustomQuestion::create([
            'interview_id' => $interview->id,
            'question' => $question['question'],
            'type' => $question['type'],
            'mark' => $question['mark'] ?? 0,
          ]);
        }
      }
      DB::commit();
      return ResponseMessage("Interview submitted successfully", 201);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function getInterviewResults($request)
  {
    $interviews = Interview::with(['staff.department', 'staff.roles', 'exam', 'interviewAnswers.examQuestion', 'interviewAnswers.answer', 'customQuestions'])
      ->when($request->has('exam_id'), function ($query) use ($request) {
        $query->where('exam_id', $request->exam_id);
      })
      ->when($request->has('staff_id'), function ($query) use ($request) {
        $query->where('staff_id', $request->staff_id);
      })
      ->when($request->has('role_id'), function ($query) use ($request) {
        $query->whereHas('staff.roles', function ($roleQuery) use ($request) {
          $roleQuery->where('id', $request->role_id);
        });
      })
      ->when($request->has('department_id'), function ($query) use ($request) {
        $query->whereHas('staff.department', function ($deptQuery) use ($request) {
          $deptQuery->where('id', $request->department_id);
        });
      })
      ->orderBy('created_at', 'desc')
      ->paginate(config('common.list_count'));
    $interviews->getCollection()->transform(function ($interview) {
      $marksByType = [];

      $interviewAnswersGrouped = $interview->interviewAnswers->groupBy(function ($interviewAnswer) {
        return $interviewAnswer->examQuestion->type;
      });
      foreach ($interviewAnswersGrouped as $type => $answers) {
        $typeTotal = $answers->sum(function ($interviewAnswer) {
          return $interviewAnswer->answer->mark;
        });
        $marksByType[$type] = ($marksByType[$type] ?? 0) + $typeTotal;
      }
      $customQuestionsGrouped = $interview->customQuestions->groupBy('type');
      foreach ($customQuestionsGrouped as $type => $questions) {
        $typeTotal = $questions->sum('mark');
        $marksByType[$type] = ($marksByType[$type] ?? 0) + $typeTotal;
      }
      $totalMark = array_sum($marksByType);
      $interview->marks_by_type = $marksByType;
      $interview->total_mark = $totalMark;
      return $interview;
    });
    return $interviews;
  }
}
