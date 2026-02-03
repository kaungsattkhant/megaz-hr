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
      $existInterviews = Interview::where('staff_id', $data['staff_id'])
        ->where('exam_id', $data['exam_id'])
        ->exists();
      if ($existInterviews) {
        return ResponseMessage('Staff already took this exam. Retake is not allowed.', 400);
      }
      $exam = Exam::find($data['exam_id']);
      $grade = $exam->gradeForMark($data['total_mark']);
      $data['status'] = $this->checkPassOrFail($exam, $data['total_mark']);
      $data['grade_id'] = $grade ? $grade->id : null;
      $interview = Interview::create($data);
      if ($data['status'] == 'pass') {
        $skillIds = $exam->skills->pluck('id')->toArray();
        $interview->staff->skills()->attach($skillIds);
      }
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

  public function checkPassOrFail($exam, $totalMark)
  {
    $passMarkGrade = $exam->grades()
      ->where('is_pass', true)
      ->orderBy('mark', 'asc')
      ->first();
    if (!$passMarkGrade) {
      \ResponseMessage('No pass mark grade defined for this exam', 500);
    }
    // Define your pass mark criteria here
    $passMark = $passMarkGrade->mark; // Example: 50 is the pass mark
    return $totalMark >= $passMark ? 'pass' : 'fail';
  }

  public function getInterviewResults($request)
  {
    $query = DB::table('interviews')
      ->select([
        'staff.id as staff_id',
        'staff.name',
        'staff.department_id',
        'departments.name as department_name',
        DB::raw('
            GROUP_CONCAT(DISTINCT staff_roles.role_id) as role_ids
        '),
        DB::raw('
            GROUP_CONCAT(DISTINCT roles.name) as role_names
        '),
        DB::raw('count(interviews.id) as interview_count'),
        // Total mark for all interviews (custom questions +  answers)
        DB::raw('
                COALESCE(SUM(
                    (SELECT COALESCE(SUM(a.mark),0) FROM interview_answers ia
                    JOIN answers a ON ia.answer_id = a.id
                    JOIN exam_questions eq ON ia.exam_question_id = eq.id
                    WHERE ia.interview_id = interviews.id
                    ) +
                    (SELECT COALESCE(SUM(cq.mark),0) FROM custom_questions cq
                    WHERE cq.interview_id = interviews.id
                    )
                ),0)
                as total_mark
            '),
        // EQ total
        DB::raw('
                COALESCE(SUM(
                    (SELECT COALESCE(SUM(a.mark),0) FROM interview_answers ia
                    JOIN answers a ON ia.answer_id = a.id
                    JOIN exam_questions eq ON ia.exam_question_id = eq.id
                    WHERE ia.interview_id = interviews.id AND eq.type = "EQ"
                    ) +
                    (SELECT COALESCE(SUM(cq.mark),0) FROM custom_questions cq
                    WHERE cq.interview_id = interviews.id AND cq.type = "EQ"
                    )
                ),0)
                as eq_mark
            '),
        // IT total
        DB::raw('
                COALESCE(SUM(
                    (SELECT COALESCE(SUM(a.mark),0) FROM interview_answers ia
                    JOIN answers a ON ia.answer_id = a.id
                    JOIN exam_questions eq ON ia.exam_question_id = eq.id
                    WHERE ia.interview_id = interviews.id AND eq.type = "IT"
                    ) +
                    (SELECT COALESCE(SUM(cq.mark),0) FROM custom_questions cq
                    WHERE cq.interview_id = interviews.id AND cq.type = "IT"
                    )
                ),0)
                as it_mark
            ')
      ])
      ->join('staff', 'interviews.staff_id', '=', 'staff.id')
      ->join('exams', 'interviews.exam_id', '=', 'exams.id')
      ->leftJoin('departments', 'staff.department_id', '=', 'departments.id')
      ->leftJoin('role_staff as staff_roles', 'staff.id', '=', 'staff_roles.staff_id')
      ->leftJoin('roles', 'staff_roles.role_id', '=', 'roles.id')
      // add filter conditions
      ->when($request->has('exam_id'), function ($query) use ($request) {
        $query->where('interviews.exam_id', $request->exam_id);
      })
      ->when($request->has('staff_id'), function ($query) use ($request) {
        $query->where('interviews.staff_id', $request->staff_id);
      })
      ->when($request->has('role_id'), function ($query) use ($request) {
        $query->where('staff_roles.role_id', $request->role_id);
      })
      ->when($request->has('department_id'), function ($query) use ($request) {
        $query->where('staff.department_id', $request->department_id);
      })
      ->where('exams.type', $request->type ?? 'interview')
      ->groupBy('staff.id', 'staff.name', 'staff.department_id', 'departments.name')
      ->orderByDesc('interview_count');
    return $query->paginate(config('common.list_count', 20));
  }
}
