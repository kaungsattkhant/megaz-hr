<?php

namespace App\Repositories\Exam;

interface ExamRepositoryInterface
{
  public function getAllExams($request);

  public function getExamById($id);

  public function createExam(array $data);

  public function updateExam(array $data, $id);

  public function deleteExam($id);

  public function deleteExamSkill($id);

  public function deleteGrade($id);

  public function deleteExamQuestion($id);

  public function toggleExamQuestion($id);

  public function getExamByRole($roleId, $examType);
}
