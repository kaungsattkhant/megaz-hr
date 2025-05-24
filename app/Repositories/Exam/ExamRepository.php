<?php

namespace App\Repositories\Exam;

use App\Models\Exam;
use App\Models\ExamSkill;
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

  public function getExamById($id)
  {
    $exam = Exam::with(['examSkills.skill', 'grades', 'examQuestions.answers', 'role.department'])
      ->find($id);
    if (!$exam) {
      ResponseMessage('Exam not found', 404);
    }

    return $exam;
  }

  public function updateExam(array $data, $id)
  {
    DB::beginTransaction();
    try {
      $exam = Exam::find($id);
      if (!$exam) {
        ResponseMessage('Exam not found', 404);
      }

      $exam->update($data);
      if (isset($data['exam_skills'])) {
        $skills = json_decode($data['exam_skills'], true);
        foreach ($skills as $skill) {
          $exam->examSkills()->updateOrCreate(
            ['skill_id' => $skill],
            ['skill_id' => $skill]
          );
        }
      }
      if (isset($data['grades'])) {
        $grades = json_decode($data['grades'], true);
        foreach ($grades as $grade) {
          if (isset($grade['id'])) {
            $exam->grades()->updateOrCreate(
              ['id' => $grade['id']],
              [
                'mark' => $grade['mark'],
                'grade' => $grade['grade'],
                'is_active' => 1,
              ]
            );
          } else {
            $exam->grades()->create([
              'mark' => $grade['mark'],
              'grade' => $grade['grade'],
              'is_active' => 1,
            ]);
          }
        }
      }

      if (isset($data['exam_questions'])) {
        $exam_questions = json_decode($data['exam_questions'], true);
        foreach ($exam_questions as $question) {
          $examQuestion = $exam->examQuestions()->updateOrCreate(
            [
              'id' => $question['id'] ?? null,
            ],
            [
              'question' => $question['question'],
              'is_active' => 1,
            ]
          );
          if (isset($question['answers'])) {
            foreach ($question['answers'] as $answer) {
              $examQuestion->answers()->updateOrCreate(
                [
                  'id' => $answer['id'] ?? null,
                ],
                [
                  'answer' => $answer['answer'],
                  'mark' => $answer['mark'] ?? 0,
                  'is_active' => 1,
                ]
              );
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
  public function deleteExam($id)
  {
    DB::beginTransaction();
    try {
      $exam = Exam::find($id);
      if (!$exam) {
        ResponseMessage('Exam not found', 404);
      }
      $exam->examSkills()->delete();
      $exam->grades()->delete();
      $exam->examQuestions()->each(function ($question) {
        $question->answers()->delete();
        $question->delete();
      });
      $exam->delete();
      DB::commit();
      return ResponseMessage('Exam deleted successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function deleteExamSkill($id)
  {
    DB::beginTransaction();
    try {
      $examSkill = ExamSkill::find($id);
      if (!$examSkill) {
        ResponseMessage('Exam skill not found', 404);
      }
      $examSkill->delete();
      DB::commit();
      return ResponseMessage('Exam skill deleted successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
