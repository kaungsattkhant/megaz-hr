<?php

namespace App\Repositories\Exam;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Skill;
use App\Models\Staff;
use App\Models\Answer;
use App\Models\ExamSkill;
use App\Models\StaffExam;
use App\Models\ExamQuestion;
use App\Models\StaffExamAnswer;
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
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for Exam Skills.', 400);
        }

        foreach ($skills as $skillId) {
          $skill = Skill::find($skillId);
          if (!$skill || $skill->role_id != $exam->role_id) {
            return ResponseMessage('Skill does not match the exam role_id.', 400);
          }
        }
        if (is_array($skills)) {
          $exam->skills()->sync($skills);
        }
      }

      if (isset($data['grades'])) {
        $grades = json_decode($data['grades'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for grades.', 400);
        }
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
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for Exam Questions.', 400);
        }
        foreach ($exam_questions as $question) {
          $examQuestion = $exam->examQuestions()->create([
            'question' => $question['question'],
            'type' => $question['type'] ?? null,
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
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for Exam Skills.', 400);
        }
        foreach ($skills as $skillId) {
          $skill = Skill::find($skillId);
          if (!$skill || $skill->role_id != $exam->role_id) {
            return ResponseMessage('Skill does not match the exam role_id.', 400);
          }
        }
        if (is_array($skills)) {
          $exam->skills()->sync($skills);
        }
      }
      if (isset($data['grades'])) {
        $grades = json_decode($data['grades'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for grades.', 400);
        }
        $gradeIds = array_column($grades, 'id');
        $exam->grades()->whereNotIn('id', $gradeIds)->delete();
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
        if (json_last_error() !== JSON_ERROR_NONE) {
          return ResponseMessage('Invalid JSON data provided for Exam Questions.', 400);
        }
        $incomingQuestionIds = array_column($exam_questions, 'id');
        $exam->examQuestions()->whereNotIn('id', $incomingQuestionIds)->each(function ($question) {
          $question->answers()->delete();
          $question->delete();
        });
        foreach ($exam_questions as $question) {
          $examQuestion = $exam->examQuestions()->updateOrCreate(
            [
              'id' => $question['id'] ?? null,
            ],
            [
              'question' => $question['question'],
              'type' => $question['type'] ?? null,
              'is_active' => 1,
            ]
          );
          if (isset($question['answers'])) {
            $incomingAnswerIds = array_column($question['answers'], 'id');
            $examQuestion->answers()->whereNotIn('id', $incomingAnswerIds)->delete();
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
  public function deleteGrade($id)
  {
    DB::beginTransaction();
    try {
      $grade = Grade::find($id);
      if (!$grade) {
        ResponseMessage('Grade not found', 404);
      }
      $grade->delete();
      DB::commit();
      return ResponseMessage('Grade deleted successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function deleteExamQuestion($id)
  {
    DB::beginTransaction();
    try {
      $examQuestion = ExamQuestion::find($id);

      if (!$examQuestion) {
        ResponseMessage('Exam question not found', 404);
      }
      $examQuestion->answers()->delete();
      $examQuestion->delete();
      DB::commit();
      return ResponseMessage('Exam question deleted successfully', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
  public function toggleExamQuestion($id)
  {
    DB::beginTransaction();
    try {
      $examQuestion = ExamQuestion::find($id);
      if (!$examQuestion) {
        ResponseMessage('Exam question not found', 404);
      }
      $result = toggleColumn(ExamQuestion::class, $id, 'is_active');
      if ($result) {
        foreach ($examQuestion->answers as $answer) {
          $answerResult = toggleColumn(Answer::class, $answer->id, 'is_active');
          if (!$answerResult) {
            throw new \Exception('Failed to toggle one or more related answers.');
          }
        }

        DB::commit();
        return ResponseMessage('Exam question and related answers status successfully', 200);
      }

      return ResponseMessage('Failed to update status', 400);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getExamByRole($roleId, $examType)
  {
    return Exam::with(['examQuestions.answers'])->where('role_id', $roleId)
      ->where('type', $examType)
      ->get();
  }

  public function answerExamQuestion($request)
  {
    DB::beginTransaction();
    try {
      // prevent duplicate StaffExam for same staff and exam
      if ($this->staffExamExists($request->staff_id, $request->exam_id)) {
        ResponseMessage('Staff exam already exists for this staff and exam', 409);
      }
      $exam = Exam::find($request->exam_id);
      $grade = $exam->gradeForMark($request->total_mark);
      if(!$grade){
        ResponseMessage('No grade found for the given total mark', 404);
      }
      $staffExam= StaffExam::create([
        'staff_id'=>$request->staff_id,
        'exam_id'=>$request->exam_id,
        'grade_id'=>$grade->id,
        'total_mark'=>$request->total_mark,
      ]);
      foreach($request->answers as $answer){
        StaffExamAnswer::create([
          'staff_exam_id'=>$staffExam->id,
          'exam_question_id'=>$answer['exam_question_id'],
          'answer_id'=>$answer['answer_id'],
        ]);
      }
      DB::commit();
      return $staffExam;
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  /**
   * Check whether a StaffExam already exists for a given staff and exam.
   */
  public function staffExamExists(int $staffId, int $examId): bool
  {
    return StaffExam::where('staff_id', $staffId)
      ->where('exam_id', $examId)
      ->exists();
  }
}
