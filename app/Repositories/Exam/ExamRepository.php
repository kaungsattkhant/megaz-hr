<?php

namespace App\Repositories\Exam;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;


class ExamRepository implements ExamRepositoryInterface
{
  public function getAllExams($request)
  {
    $query = Exam::with(['examSkills.skill', 'grades', 'examQuestions.answers', 'role.department'])
      ->orderBy('created_at', 'desc');

    if ($request->has('search_input')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'LIKE', '%' . $request->search_input . '%')
          ->orWhere('type', 'LIKE', '%' . $request->search_input . '%');
      });
    }

    if ($request->has('role_id')) {
      $query->where('role_id', $request->role_id);
    }
    if ($request->has('type')) {
      $query->where('type', $request->type);
    }
    if ($request->has('department_id')) {
      $query->whereHas('role.department', function ($q) use ($request) {
        $q->where('id', $request->department_id);
      });
    }

    return $query->paginate(config('common.list_count'));
  }


  public function createExam(array $data)
  {
    DB::beginTransaction();
    try {
      $exam = Exam::create($data);
      if (isset($data['exam_skills'])) {
        $skills = json_decode($data['exam_skills'], true);

        foreach ($skills as $skill) {
          $exam->examSkills()->create([
            'skill_id' => $skill,
          ]);
        }
      }

      if (isset($data['grades'])) {
        $grades = json_decode($data['grades'], true);
        foreach ($grades  as $grade) {
          $exam->grades()->create([
            'mark' => $grade['mark'],
            'grade' => $grade['grade'],
            'is_active' => 1,
          ]);
        }
      }

      if (isset($data['exam_questions'])) {
        $exam_questions = json_decode($data['exam_questions'], true);
        foreach ($exam_questions as $question) {
          $examQuestion = $exam->examQuestions()->create([
            'question' => $question['question'],
            'is_active' => 1,
          ]);
          if (isset($question['answers'])) {

            foreach ($question['answers'] as $answer) {
              $examQuestion->answers()->create([
                'answer' => $answer['answer'],
                'mark' => $answer['mark'] ?? 0,
                'is_active' => 1,
              ]);
            }
          }
        }
      }
      DB::commit();
      return $exam;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
